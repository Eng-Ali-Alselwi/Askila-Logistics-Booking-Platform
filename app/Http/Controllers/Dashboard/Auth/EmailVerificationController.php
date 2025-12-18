<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class EmailVerificationController extends Controller
{
        /**
     * Show the email verification notice view.
     */
    public function show()
    {
        return view('dashboard.auth.verify-email');
    }

    /**
     * Handle the email verification link.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/dashboard');
    }

    /**
     * Resend the verification email.
     */
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'A new verification link has been sent to your email address');
    }
}
