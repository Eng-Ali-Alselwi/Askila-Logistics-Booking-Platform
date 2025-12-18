<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switchLanguage($lang)
    {
        if (!in_array($lang, ['en', 'ar'])) {
            $lang = 'en';
        }

        Session::put('locale', $lang);
        App::setLocale($lang);

        return redirect()->back();
    }
}
