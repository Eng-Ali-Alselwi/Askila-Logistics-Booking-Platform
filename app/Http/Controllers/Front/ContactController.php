<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageMail;

class ContactController extends Controller
{
    public function index() {
        return view('front.contact.contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $toAddress = config('mail.to.address')
            ?? env('MAIL_TO_ADDRESS')
            ?? config('mail.from.address');

        try {
            Mail::to($toAddress)->send(new ContactMessageMail(
                $validated['name'],
                $validated['email'],
                $validated['message']
            ));

            return back()->with('success', __('messages.contact_sent_successfully'));
        } catch (\Throwable $e) {
            \Log::error('Contact form email failed', [
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => __('messages.contact_send_failed')]);
        }
    }
}
