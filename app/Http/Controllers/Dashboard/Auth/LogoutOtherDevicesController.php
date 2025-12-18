<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LogoutOtherDevicesController extends Controller
{
        public function logout(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'كلمة المرور غير صحيحة',
            ], 422);
        }

        // يسجل الخروج من كل الجلسات الأخرى
        Auth::logoutOtherDevices($request->password);

        return response()->json([
            'message' => 'تم تسجيل الخروج من جميع الأجهزة الأخرى بنجاح.',
        ]);
    }
}
