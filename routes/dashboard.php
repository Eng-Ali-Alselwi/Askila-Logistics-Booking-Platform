<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\Users\UsersController;
use App\Http\Controllers\Dashboard\Users\RolesController;
use App\Http\Controllers\Dashboard\Shipments\ShipmentsController;
use App\Http\Controllers\Dashboard\Flights\FlightController;
use App\Http\Controllers\Dashboard\CustomersController;
use App\Http\Controllers\Dashboard\BranchesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\ReportsController;
use App\Http\Controllers\Dashboard\BookingController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestPermissionsController;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware( ['auth:web', 'auth.session', 'verified','is_active'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        // Route::group(['prefix'=> 'profile','as'=> 'profile.'], function () {
        //     Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
        //     Route::put('/profile', [ProfileController::class, 'update'])->name('update');
        //     Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('avatar');
        //     Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('password');
        //     Route::post('/profile/logout-others', [ProfileController::class, 'logoutOtherSessions'])->name('logout-others');
        // });
        // Users Management
        Route::middleware(['permission:manage users'])->group(function () {
            Route::resource('users', UsersController::class);
            Route::patch('users/{user}/toggle-status', [UsersController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        // Roles Management
        Route::middleware(['permission:manage roles'])->group(function () {
            Route::resource('roles', RolesController::class);
        });

        // Shipments Management
        // View shipments - for roles that can only view
        Route::middleware(['permission:view shipments'])->group(function () {
            Route::get('shipments', [ShipmentsController::class, 'index'])->name('shipments.index');
            // Constrain param so it won't catch reserved words like 'create'
            Route::get('shipments/{shipment}', [ShipmentsController::class, 'show'])
                ->whereUlid('shipment')
                ->name('shipments.show');
        });
        
        // Create shipments - for roles that can create
        Route::middleware(['permission:create shipments'])->group(function () {
            Route::get('shipments/create', [ShipmentsController::class, 'create'])->name('shipments.create');
            Route::post('shipments', [ShipmentsController::class, 'store'])->name('shipments.store');
        });
        
        // Edit shipments - for roles that can edit
        Route::middleware(['permission:edit shipments'])->group(function () {
            Route::get('shipments/{shipment}/edit', [ShipmentsController::class, 'edit'])->name('shipments.edit');
            Route::put('shipments/{shipment}', [ShipmentsController::class, 'update'])->name('shipments.update');
        });
        
        // Delete shipments - for roles that can delete
        Route::middleware(['permission:delete shipments'])->group(function () {
            Route::delete('shipments/{shipment}', [ShipmentsController::class, 'destroy'])->name('shipments.destroy');
        });
        
        // Update shipment status - for roles that can update status
        Route::middleware(['permission:update shipment status'])->group(function () {
            Route::post('shipments/{shipment}/update-status', [ShipmentsController::class, 'updateStatus'])->name('shipments.update-status');
        });
        
        // Export shipments - for roles that can export
        Route::middleware(['permission:export shipments'])->group(function () {
            Route::get('shipments/export', [ShipmentsController::class, 'export'])->name('shipments.export');
        });

        // Customers Management
        Route::middleware(['permission:manage customers'])->group(function () {
            Route::resource('customers', CustomersController::class);
            Route::patch('customers/{customer}/toggle-status', [CustomersController::class, 'toggleStatus'])->name('customers.toggle-status');
        });

        // Branches Management
        Route::middleware(['permission:manage branches'])->group(function () {
            Route::resource('branches', BranchesController::class);
            Route::patch('branches/{branch}/toggle-status', [BranchesController::class, 'toggleStatus'])->name('branches.toggle-status');
        });

        // Settings Management
        Route::middleware(['permission:manage settings'])->group(function () {
            Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        });

        // Flights Management
        Route::middleware(['permission:manage flights'])->group(function () {
            Route::resource('flights', FlightController::class);
            Route::patch('flights/{flight}/toggle-status', [FlightController::class, 'toggleStatus'])->name('flights.toggle-status');
        });

        // Bookings Management
        Route::middleware(['permission:manage bookings'])->group(function () {
            Route::resource('bookings', BookingController::class);
            Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
            Route::post('bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
            Route::post('bookings/{booking}/refund', [BookingController::class, 'refund'])->name('bookings.refund');
            Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
        });

        // Reports Management
        Route::middleware(['permission:view reports|view branch reports'])->group(function () {
            Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
        });
        Route::middleware(['permission:export reports'])->group(function () {
            Route::get('reports/export', [ReportsController::class, 'export'])->name('reports.export');
        });

        // يمكنك إضافة المزيد من الروابط هنا مثل:
        // Route::resource('users', UserController::class);
        // Route::prefix('users')->name('users.')->controller(UserController::class)->group(function () {
            // Route::get('/', 'index')->name('index');
            // Route::get('/orders/{id}', 'show');
            // Route::post('/orders', 'store');
        // });
        
        // Test route for permissions
        Route::get('test-shipments', [TestPermissionsController::class, 'testShipments'])->name('test.shipments');
    });