<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUserRequest;
use App\Http\Requests\Dashboard\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $users = User::with(['roles', 'permissions'])->latest()->paginate(15);
        return view('dashboard.users.index');
        // $users = User::all(); // Assuming you have a User model
        // return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        // $roles = Role::pluck('name', 'id');
        return view('dashboard.users.create',);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($data);
        $user->assignRole(Role::findById($data['role_id'])->name);

        return redirect()->route('dashboard.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'id');
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($data);

        // تحديث الدور
        $user->syncRoles([Role::findById($data['role_id'])->name]);

        return redirect()->route('dashboard.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('dashboard.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleActivation(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return redirect()->back()->with('success', 'تم تغيير حالة التفعيل بنجاح');
    }
}
