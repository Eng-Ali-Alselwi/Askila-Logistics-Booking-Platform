<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserRolesSelect extends Component
{

    public int $userId;
    /** @var array<string> */
    public array $selected = [];
    /** @var array<string> */
    public array $allRoles = [];
    public bool $isMainSuperAdmin = false;

    public function mount(int $userId): void
    {
        // dd(auth()->user()->getAllPermissions()->toJson());
        abort_unless(auth()->user()?->can('manage users'), 403);

        $this->userId = $userId;
        $user = User::with('roles')->findOrFail($this->userId);

        // تحديد إذا كان هذا هو السوبر أدمن الرئيسي
        $this->isMainSuperAdmin = $user->hasRole('super_admin');

        // جلب كل الأدوار باستثناء super_admin نهائيًا
        $this->allRoles = Role::where('name', '!=', 'super_admin')
            ->pluck('name')
            ->toArray();

        $this->selected = $user->roles->pluck('name')->toArray();
    }

    /** حفظ فوري عند كل تغيير */
    public function updatedSelected(): void
    {
        $this->save();
    }

    public function save(): void
    {

        if ($this->isMainSuperAdmin) {
            // منع أي تعديل على السوبر أدمن الرئيسي
            return;
        }

        $user = User::with('roles')->findOrFail($this->userId);

        // فلترة أي قيم غير موجودة ضمن الأدوار
        $valid = array_values(array_intersect(
            $this->selected,
            Role::where('name', '!=', 'super_admin')->pluck('name')->toArray()
        ));

        $user->syncRoles($valid);

        // $this->dispatch('toast', type: 'success', message: 'تم تحديث الأدوار.');
    }

    public function render()
    {
        return view('livewire.user-roles-select');
    }
}
