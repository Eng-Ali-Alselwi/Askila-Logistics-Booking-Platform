<?php

use Illuminate\Support\Facades\App;

if (!function_exists('t')) {
  function t($key)
  {
    // Use the current locale that was set by the middleware
    $result = __('messages.' . $key);
    return str_starts_with($result, 'messages.') ? $key : $result;
  }
}



if (!function_exists('getDay')) {
  function getDay($day)
  {
    switch ($day) {
      case 1:
        return t('Saturday');
      case 2:
        return t('Sunday');
      case 3:
        return t('Monday');
      case 4:
        return t('Tuesday');
      case 5:
        return t('Wednesday');
      case 6:
        return t('Thursday');
    }
  }
}

if (!function_exists('setting')) {
  function setting($key, $default = null)
  {
    return \App\Models\Setting::get($key, $default);
  }
}


