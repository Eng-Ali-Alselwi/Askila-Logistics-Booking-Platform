<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_id_number' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:20',
            'image' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date|after:passport_issue_date',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'current_residence_country' => 'nullable|string|max:100',
            'destination_country' => 'nullable|string|max:100',
            'phone_sudan' => 'nullable|string|max:20',
            'travel_date' => 'nullable|date|after_or_equal:today',
            'ticket_type' => 'required|in:one_way,round_trip',
            'seat_class' => 'required|string',
            'cabin_type' => 'nullable|string|max:100',
            'number_of_passengers' => 'required|integer|min:1|max:9',
            'passenger_details' => 'nullable|array',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:paypal,credit_card,whatsapp'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'passenger_name.required' => 'يجب إدخال الاسم الكامل',
            'passenger_email.required' => 'يجب إدخال البريد الإلكتروني',
            'passenger_email.email' => 'البريد الإلكتروني غير صحيح',
            'passenger_phone.required' => 'يجب إدخال رقم الهاتف',
            'image.required' => 'حقل "صورة الجواز او صورة الإقامة" مطلوب.',
            'image.file' => 'الملف المرفوع غير صالح.',
            'image.mimes' => 'يجب أن تكون الصورة بصيغة jpg أو jpeg أو png.',
            'image.max' => 'حجم الملف يجب ألا يتجاوز 2 ميغابايت.',
            'ticket_type.required' => 'يجب اختيار نوع التذكرة',
            'ticket_type.in' => 'نوع التذكرة غير صحيح',
            'seat_class.required' => 'يجب اختيار فئة المقعد',
            'number_of_passengers.required' => 'يجب إدخال عدد الركاب',
            'number_of_passengers.integer' => 'عدد الركاب يجب أن يكون رقماً',
            'number_of_passengers.min' => 'عدد الركاب يجب أن يكون 1 على الأقل',
            'number_of_passengers.max' => 'عدد الركاب يجب أن يكون 9 كحد أقصى',
            'payment_method.required' => 'يجب اختيار طريقة الدفع',
            'payment_method.in' => 'طريقة الدفع غير صحيحة',
            'passport_expiry_date.after' => 'تاريخ انتهاء الجواز يجب أن يكون بعد تاريخ الإصدار',
            'travel_date.after_or_equal' => 'تاريخ السفر يجب أن يكون اليوم أو بعده'
        ];
    }
}
