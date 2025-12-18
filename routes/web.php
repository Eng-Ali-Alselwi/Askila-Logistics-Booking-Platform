<?php

use App\Http\Controllers\Dashboard\auth\EmailVerificationController;
use App\Http\Controllers\Dashboard\auth\ForgotPasswordController;
use App\Http\Controllers\Dashboard\Auth\LoginController;
use App\Http\Controllers\Dashboard\Auth\LogoutOtherDevicesController;
use App\Http\Controllers\Dashboard\Auth\RegisterController;
use App\Http\Controllers\Dashboard\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ServicesController;
use App\Http\Controllers\LanguageController;
use App\Services\Sms\MoraSmsClient;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\StripeController;
// use Symfony\Component\HttpKernel\Profiler\Profile;

// use App\Mail\TestMail;
// use Illuminate\Support\Facades\Mail;


Route::get('paypal/payment', [PayPalController::class, 'createPayment'])->name('paypal.payment');
Route::get('paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
Route::get('paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');

// مسارات Stripe
Route::post('/stripe/create-payment-intent/{booking}', [StripeController::class, 'createPaymentIntent'])->name('stripe.create-payment-intent');
Route::post('/stripe/confirm-payment/{booking}', [StripeController::class, 'confirmPayment'])->name('stripe.confirm-payment');
Route::post('/stripe/cancel-payment/{booking}', [StripeController::class, 'cancelPayment'])->name('stripe.cancel-payment');
Route::get('/stripe/check-status/{booking}', [StripeController::class, 'checkPaymentStatus'])->name('stripe.check-status');


Route::middleware('guest:web')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
    Route::get('/about', [AboutController::class, 'index'])->name('about.index');
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/faq', function () {
        return view('front.faq');
    })->name('faq.index');
    Route::get('/track', [App\Http\Controllers\Front\ShipmentController::class, 'track'])->name('shipment.track');
    Route::get('/track2/{tracking_number?}', function (?string $tracking_number = null) {
        return view('front.shipment.track2', ['prefill' => $tracking_number]);
    })->name('shipment.track2');

    // مسارات نظام التذاكر
    Route::get('/flights', [App\Http\Controllers\Front\FlightController::class, 'index'])->name('flights.index');
    Route::post('/flights/search', [App\Http\Controllers\Front\FlightController::class, 'search'])->name('flights.search');
    Route::get('/flights/{flight}', [App\Http\Controllers\Front\FlightController::class, 'show'])->name('flights.show');
    Route::post('/flights/{flight}/book', [App\Http\Controllers\Front\FlightController::class, 'book'])->name('flights.book');
    Route::post('/flights/track', [App\Http\Controllers\Front\FlightController::class, 'track'])->name('flights.track');
    
    // مسارات الحجوزات
    Route::get('/bookings/track', [App\Http\Controllers\Front\BookingController::class, 'trackForm'])->name('booking.track');
    Route::post('/bookings/track', [App\Http\Controllers\Front\FlightController::class, 'track'])->name('booking.track.search');
    Route::get('/bookings/track/success', [App\Http\Controllers\Front\BookingController::class, 'trackWithSuccess'])->name('booking.track.success');
    Route::get('/bookings/{booking}/payment', [App\Http\Controllers\Front\BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/bookings/{booking}/payment', [App\Http\Controllers\Front\BookingController::class, 'processPayment'])->name('booking.payment.process');
    Route::get('/bookings/{booking}/confirmation', [App\Http\Controllers\Front\BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Front\BookingController::class, 'cancel'])->name('booking.cancel');
    // WhatsApp booking request route
    Route::post('/bookings/{booking}/whatsapp-request', [App\Http\Controllers\Front\BookingController::class, 'whatsappRequest'])->name('booking.whatsapp.request');
    
    // مسار اختيار طريقة الدفع
    Route::post('/flights/{flight}/choose-payment', [App\Http\Controllers\Front\BookingController::class, 'choosePayment'])->name('flights.choosePayment');
    
    // مسار الدفع بالبطاقة الائتمانية
    Route::get('/payments/credit-card/{booking}', [App\Http\Controllers\Front\BookingController::class, 'creditCardPayment'])->name('payment.credit-card');
    
    // مسار تأكيد الحجز عبر الواتساب
    Route::get('/bookings/{booking}/whatsapp-confirmation', [App\Http\Controllers\Front\BookingController::class, 'whatsappConfirmation'])->name('booking.whatsapp.confirmation');

});

Route::get('/test-sms', function () {
    $client = app(MoraSmsClient::class);

    // 🔴 عدل الرقم هنا لرقمك (بصيغة دولية مثل: 9665XXXXXXXX)
    $myPhone = '966501828276';
    $message = 'اختبار Mora SMS: مرحباً من نظام أسكلة 🚀';

    $result = $client->send($myPhone, $message);

    return response()->json($result);
});

// Test route to create admin user and check database
Route::get('/create-test-user', function () {
    try {
        // Check if user already exists
        $existingUser = \App\Models\User::where('email', 'admin@askila.com')->first();
        
        if ($existingUser) {
            return response()->json([
                'status' => 'user_exists',
                'message' => 'Test user already exists',
                'user' => [
                    'name' => $existingUser->name,
                    'email' => $existingUser->email,
                    'is_active' => $existingUser->is_active
                ]
            ]);
        }
        
        // Create test user
        $user = \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@askila.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
            'phone' => '+966501234567'
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test user created successfully',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active
            ],
            'credentials' => [
                'email' => 'admin@askila.com',
                'password' => 'password123'
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create user: ' . $e->getMessage()
        ]);
    }
});

Route::get('lang/{lang}', [LanguageController::class, 'switchLanguage'])->name('lang.switch');

// 🔽 استدعاء ملفات الروابط الأخرى
require __DIR__ . '/auth.php';
require __DIR__ . '/dashboard.php';

// Route::middleware('guest:web')->group(function () {

//     Route::get('/', [HomeController::class, 'home'])->name('home');
// });

// Route::get('lang/{lang}', [LanguageController::class, 'switchLanguage'])->name('lang.switch');



// Route::group(['prefix'=> '/dashboard','as'=>'dashboard.','middleware'=>['auth:web','auth.session','verified']], function () {

//     Route::get('/', [DashboardController::class,'index'])->name('index');

//     Route::get('/profile', [ProfileController::class,'index'])->name('profile');

// });

Route::middleware(['guest:web'])->group(function () {
     Route::get('/login',[LoginController::class,'showLoginForm'])->name('login')->withoutMiddleware( ['auth:web','verified']);
     Route::post('/login/store',[LoginController::class,'login'])->name('login.authenticate')->withoutMiddleware(['auth:web','verified']);
});

// Route::middleware(['guest:web'])->group(function () {

//     Route::get('/register',[RegisterController::class,'showRegisterForm'])->name('register')->withoutMiddleware( ['auth:web','verified']);

//     Route::post('/register/store',[RegisterController::class,'register'])->name('register.authenticate')->withoutMiddleware(['auth:web','verified']);

// });

// Route::middleware('auth:web')->group(function () {

//     Route::post('/logout',[LoginController::class,'logout'])->name('logout')->withoutMiddleware( 'verified');

//     Route::post( '/logout-other-devices', [LogoutOtherDevicesController::class, 'logout'])->name('logout.other.devices');

// });

// Route::get('/email/verify', [EmailVerificationController::class, 'show'])->middleware( 'auth')->name('verification.notice');

// Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

// Route::post('/email/verification-notification',  [EmailVerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route::get('/forgot-password', [ForgotPasswordController::class,'showForgotForm'])->middleware('guest')->name('password.request');

// Route::post('/forgot-password',[ForgotPasswordController::class,'forgot'])->middleware('guest')->name('password.email');

// Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->middleware('guest')->name('password.reset');

// Route::post('/reset-password', [ResetPasswordController::class,'reset'])->middleware('guest')->name('password.update');

// Route::get('/test-mail', function () {
//     Mail::raw('This is a test email', function ($message) {
//         $message->to('mujahidgeneral1@gmail.com')
//                 ->subject('Test Mail');
//     });

//     return 'Mail sent!';
// });

// Route::get('/', [HomeController::class, 'home'])->name('front.home');


// Route::get('/send-test-mail', function () {
//     Mail::to('mujahidalhilaly@gmail.com')->send(new TestMail());
//     return 'Test email sent!';
// });






