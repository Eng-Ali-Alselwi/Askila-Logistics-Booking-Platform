<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
         if (!preg_match('/^\+?[0-9]{7,15}$/', $value)) {
            $fail("رقم الهاتف غير صالح. الرجاء إدخال رقم صحيح يحتوي على 7 إلى 15 رقم، ويمكن أن يبدأ بـ +");
        }
    }
}
