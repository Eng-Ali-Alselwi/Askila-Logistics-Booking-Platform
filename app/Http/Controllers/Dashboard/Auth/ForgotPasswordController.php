<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('dashboard.auth.forgot-password');
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        Mail::to($request['email'])->send(new TestMail());
// $user = \App\Models\User::where('email', $request->email)->first();
// dd($user);
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
