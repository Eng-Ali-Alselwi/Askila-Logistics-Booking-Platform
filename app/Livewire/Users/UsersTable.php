<?php

namespace App\Livewire\Users;

use App\Actions\Users\ToggleUserActive;
use App\Enums\RoleEnum;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersTable extends Component
{
    use WithPagination, AuthorizesRequests;

    public int $perPage = 12;

    public string $search = '';
    public ?string $role = null;
    public ?string $status = null;

    public function render()
    {
        $query = User::query()
            ->with(['roles:id,name', 'roles.permissions:id,name', 'permissions:id,name'])
            ->when($this->search, fn($q) =>
                $q->where(function($w){
                    $w->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('phone', 'like', '%'.$this->search.'%');
                })
            )
            ->when($this->role, fn($q) => $q->role($this->role))
            ->when($this->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderByDesc('id');

        return view('livewire.users.users-table', [
            'users' => $query->paginate($this->perPage),
            'roles' => collect(RoleEnum::cases())->map(fn($c) => $c->value),
        ]);
    }
    private function notify($type,$message){
        $this->dispatch('notify', body: [
            'message'=>$message,
            'type' => $type,
        ]);
    }

    public function toggle(User $user, ToggleUserActive $toggle): void
    {
        $toggle->execute($user);
        $this->notify('success',t('Status Updated Successfully'));

    }

    public function changeRole(User $user, string $role): void
    {
        if (! in_array($role, collect(RoleEnum::cases())->pluck('value')->all(), true)) {
            return;
        }
        if ($user->hasRole(RoleEnum::SUPER_ADMIN->value)) {
            $this->notify('warning',t('Super admin role cannot be modified'));
            return;
        }
        $user->syncRoles([$role]);
        $this->notify('success',t('Role Updated Successfully'));
    }

    public function delete(User $user): void
    {
        if ($user->hasRole(RoleEnum::SUPER_ADMIN->value)) {
            $this->notify('warning',t('Super admin cannot be deleted'));
            return;
        }
        $user->delete();
        $this->resetPage();
        $this->notify('success',t('User Deleted Successfully'));
    }

    public function resetFilters(): void
    {
        $this->reset(['search','role','status']);
        $this->resetPage();
    }

    public function updatedPerPage() { $this->resetPage(); }
    public function clearRole()  { $this->role = null;   $this->resetPage(); }
    public function clearStatus(){ $this->status = null; $this->resetPage(); }
    public function clearSearch(){ $this->search = '';   $this->resetPage(); }
}
