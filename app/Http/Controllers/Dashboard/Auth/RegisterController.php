<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function showRegisterForm()
    {
        return view('dashboard.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);
        return redirect()->route('verification.notice');


        // return redirect()->route('dashboard.index');
    }

}
