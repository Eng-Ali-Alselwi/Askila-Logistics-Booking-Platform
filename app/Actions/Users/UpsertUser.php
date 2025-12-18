<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password; // لإرسال رابط إعادة تعيين كلمة المرور
use Illuminate\Support\Facades\Hash;


class UpsertUser
{
    /**
     * @param  array{
     *  name:string,
     *  email:string,
     *  phone?:string|null,
     *  password?:string|null,
     *  is_active?:bool,
     *  role:string
     * }  $data
     */
    public function execute(?User $user, array $data): User
    {
        $isCreating = empty($user) || ! $user->exists;

        $payload = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ];

        if (empty($user)) {
            $user = new User();
        }

        // كلمة المرور:
        // - في التعديل: اختيارية إذا وُجدت نحدّثها.
        // - في الإنشاء: لو ما جت → نولّد مؤقتًا (ونرسل رابط إعادة تعيين مباشرة).
        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        } elseif ($isCreating) {
            // Laravel 10 عنده Str::password()، وإلا نستخدم Str::random()
            $temp = method_exists(Str::class, 'password')
                ? Str::password(12)
                : Str::random(16);
            $payload['password'] = Hash::make($temp);
        }

        $user->fill($payload)->save();

        // ضبط الدور (دائمًا دور واحد هنا)
        $role = $data['role'] ?? RoleEnum::SENDER->value;
        $user->syncRoles([$role]);

        // بعد الإنشاء مباشرة: إرسال رابط إعادة تعيين كلمة المرور للإيميل
        if ($isCreating) {
            // سيولّد توكن ويرسل الإيميل باستخدام القناة المعرفة لديك
            Password::sendResetLink(['email' => $user->email]);
        }

        return $user->refresh();
    }
}
