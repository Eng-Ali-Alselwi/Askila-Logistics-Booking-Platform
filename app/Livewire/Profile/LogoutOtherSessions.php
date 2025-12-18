<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LogoutOtherSessions extends Component
{
    public string $password = '';

    protected function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }

    public function logoutOtherSessions(): void
    {
        $this->validate();

        // 🔒 احفظ معرف الجلسة الحالية قبل أي عمليات قد تدوّر الـ session id
        $currentSessionId = session()->getId();

        // يسجّل خروج باقي الأجهزة ويبقي الحالية
        Auth::logoutOtherDevices($this->password);

        // لو درايفر الجلسات Database: احذف كل جلسات المستخدم ما عدا الحالية
        if (Config::get('session.driver') === 'database') {
            $connection = Config::get('session.connection'); // قد تكون null
            $table = Config::get('session.table', 'sessions');

            DB::connection($connection)
                ->table($table)
                ->where('user_id', auth()->id())
                ->where('id', '!=', $currentSessionId) // ✅ استخدم المُعرّف المثبّت
                ->delete();
        }

        // (اختياري) إن كنت تستخدم Sanctum وتريد حذف كل التوكنز ما عدا الحالي:
        // if (method_exists(auth()->user(), 'tokens')) {
        //     $currentTokenId = optional(request()->user()->currentAccessToken())->id;
        //     auth()->user()->tokens()->when($currentTokenId, fn($q) => $q->where('id', '!=', $currentTokenId))->delete();
        // }

        // لا تُعد توليد/إبطال الجلسة هنا لكي لا تفقد الجلسة الحالية
        // request()->session()->regenerate() غير مطلوب لهذه العملية
        // $this->validate();

        // // يسجّل خروج باقي الأجهزة عبر الحارس الافتراضي
        // Auth::logoutOtherDevices($this->password);

        // // حذف جلسات قاعدة البيانات إن كان الدرايفر database
        // if (Config::get('session.driver') === 'database') {
        //     $connection = Config::get('session.connection'); // قد تكون null => تستخدم الاتصال الافتراضي
        //     $table = Config::get('session.table', 'sessions');

        //     DB::connection($connection)
        //         ->table($table)
        //         ->where('user_id', auth()->id())
        //         ->where('id', '!=', session()->getId())
        //         ->delete();
        // }

        // تنظيف الحقل + إشعار + إغلاق المودال (لو عندك جسر JS للمودالات)
        $this->reset('password');
        $this->dispatch('toast', body: __('Logged out from other devices.'));
        $this->dispatch('modal:close', id: 'authentication-modal');
    }

    public function render()
    {
        return view('livewire.profile.logout-other-sessions');
    }
}
