<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Component;


class UpdatePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            // يتحقق تلقائيًا من كلمة المرور الحالية للمستخدم الحالي
            'current_password' => ['required', 'current_password'],
            // استخدم سياسة لارافيل الافتراضية لكلمات المرور القوية
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ];
    }

    public function updatePassword(): void
    {
        $validated = $this->validate();

        // تحديث كلمة المرور
        auth()->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();
        // auth()->user()->logoutOtherDevices($this->current_password);

        // تنظيف الحقول
        $this->reset('current_password', 'password', 'password_confirmation');

        // تنبيه واجهة
        $this->dispatch('toast', body: __('Your password has been updated.'));
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
