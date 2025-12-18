<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Dashboard\StoreUserRequest;
use App\Http\Requests\Dashboard\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when(request('role'), function ($query, $role) {
                $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->latest()
            ->paginate(15);

        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->roles) {
            $roleIds = (array) $request->roles;
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $roleIds)->pluck('name')->all();
            if (!empty($roles)) {
                $user->assignRole($roles);
            }
        }

        event(new Registered($user));

        return redirect()->route('dashboard.users.index')
            ->with('success', t('User created successfully'));
    }

    public function show(User $user)
    {
        $user->load('roles');
        return view('dashboard.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $user->load('roles');
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        // Add debugging to see what's happening
        Log::info('User update request received', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'request_data' => $request->all(),
            'route_parameters' => $request->route()->parameters()
        ]);
        
        $data = $request->validated();
        
        // Handle password update
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Handle is_active checkbox properly - only set to true if checkbox is checked and has value '1'
        $data['is_active'] = $request->has('is_active') && $request->input('is_active') == '1' ? true : false;

        $user->update($data);

        // Handle roles
        if ($request->roles) {
            $roleIds = (array) $request->roles;
            $roles = \Spatie\Permission\Models\Role::whereIn('id', $roleIds)->pluck('name')->all();
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('dashboard.users.index')
            ->with('success', t('User updated successfully'));
    }

    public function destroy(User $user)
    {
        Log::info('User destroy request received', [
            'user_id' => $user->id,
            'request_method' => request()->method(),
            'route_parameters' => request()->route()->parameters()
        ]);
        
        if ($user->hasRole('super_admin')) {
            return back()->withErrors(['error' => 'Super admin cannot be deleted']);
        }

        $user->delete();

        return redirect()->route('dashboard.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus(User $user)
    {
        if ($user->hasRole('super_admin')) {
            return back()->withErrors(['error' => 'Super admin cannot be deactivated']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "User {$status} successfully.");
    }
}
