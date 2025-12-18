<?php

namespace App\Actions\Users;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ToggleUserActive
{
    public function execute(User $user): User
    {
        // لا نسمح بتعطيل السوبر أدمن (حماية من قفل النظام)
        if ($user->hasRole(RoleEnum::SUPER_ADMIN->value)) {
            throw ValidationException::withMessages([
                'user' => t('Super admin account cannot be disabled'),
            ]);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        return $user->refresh();
    }
}
