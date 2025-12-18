<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Enums\RoleEnum;
use App\Actions\Users\UpsertUser;

class CreateUser extends Component
{
    public array $form = [
        'name'      => '',
        'email'     => '',
        'phone'     => '',
        'is_active' => true,
        'role'      => null,
    ];

    public function mount(): void
    {
        $this->form['role'] = RoleEnum::SENDER->value;
    }

    protected function rules(): array
    {
        return [
            'form.name'      => ['required', 'string', 'max:255'],
            'form.email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'form.phone'     => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')],
            'form.is_active' => ['boolean'],
            'form.role'      => [Rule::in(collect(RoleEnum::cases())->pluck('value')->all())],
        ];
    }

    protected function messages(): array
    {
        return [
            'form.name.required'  => 'الاسم مطلوب',
            'form.email.required' => 'الإيميل مطلوب',
            'form.email.email'    => 'صيغة الإيميل غير صحيحة',
            'form.email.unique'   => 'الإيميل مستخدم مسبقًا',
            'form.phone.unique'   => 'رقم الجوال مستخدم مسبقًا',
            'form.role.in'        => 'الدور المحدد غير صالح',
        ];
    }

    public function save(UpsertUser $upsert)
    {
        $this->validate();

        // إنشاء مستخدم جديد — UpsertUser سيرسل Password Reset Link تلقائيًا
        $upsert->execute(null, $this->form);

        session()->flash('success',t('The user has been added and a password reset link has been sent to his email'));
        return redirect()->route('dashboard.users.index');
    }

    public function render()
    {
        $roles = collect(RoleEnum::cases())->map(fn($c) => $c->value);

        return view('livewire.users.create-user', compact('roles'));
    }
}
