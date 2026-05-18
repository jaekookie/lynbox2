<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DeliveryAddressController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BoxController as AdminBoxController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home.redirect');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::get('{subscription}', [SubscriptionController::class, 'show'])->name('show');
        Route::post('{subscription}/pause', [SubscriptionController::class, 'pause'])->name('pause');
        Route::post('{subscription}/reactivate', [SubscriptionController::class, 'reactivate'])->name('reactivate');
        Route::post('{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('{subscription}/renewal-date', [SubscriptionController::class, 'modifyRenewalDate'])->name('modify-renewal-date');
    });

    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [DeliveryController::class, 'index'])->name('index');
        Route::get('{delivery}', [DeliveryController::class, 'show'])->name('show');
        Route::get('{delivery}/track', [DeliveryController::class, 'track'])->name('track');
    });

    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/create', [ReviewController::class, 'create'])->name('create');
        Route::post('/', [ReviewController::class, 'store'])->name('store');
        Route::get('{review}', [ReviewController::class, 'show'])->name('show');
        Route::get('{review}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::patch('{review}', [ReviewController::class, 'update'])->name('update');
        Route::delete('{review}', [ReviewController::class, 'destroy'])->name('destroy');
        Route::post('{review}/helpful', [ReviewController::class, 'markHelpful'])->name('helpful');
    });

    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [DeliveryAddressController::class, 'index'])->name('index');
        Route::get('/create', [DeliveryAddressController::class, 'create'])->name('create');
        Route::post('/', [DeliveryAddressController::class, 'store'])->name('store');
        Route::get('{address}/edit', [DeliveryAddressController::class, 'edit'])->name('edit');
        Route::patch('{address}', [DeliveryAddressController::class, 'update'])->name('update');
        Route::post('{address}/default', [DeliveryAddressController::class, 'setDefault'])->name('default');
        Route::delete('{address}', [DeliveryAddressController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('{invoice}/download', [InvoiceController::class, 'download'])->name('download');
        Route::post('{invoice}/retry', [InvoiceController::class, 'retry'])->name('retry');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/setup-intent', [PaymentController::class, 'setupIntent'])->name('setup-intent');
        Route::post('/attach-method', [PaymentController::class, 'attachPaymentMethod'])->name('attach-method');
        Route::post('/delete-method', [PaymentController::class, 'deletePaymentMethod'])->name('delete-method');
        Route::post('/set-default', [PaymentController::class, 'setDefaultPaymentMethod'])->name('set-default');
    });

    Route::prefix('loyalty')->name('loyalty.')->group(function () {
        Route::get('/', [App\Http\Controllers\LoyaltyController::class, 'index'])->name('index');
    });

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/settings', [App\Http\Controllers\AccountController::class, 'settings'])->name('settings');
        Route::post('/profile', [App\Http\Controllers\AccountController::class, 'updateProfile'])->name('update-profile');
        Route::post('/email', [App\Http\Controllers\AccountController::class, 'updateEmail'])->name('update-email');
        Route::post('/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('update-password');
        Route::post('/privacy', [App\Http\Controllers\AccountController::class, 'updatePrivacy'])->name('update-privacy');
        Route::post('/notifications', [App\Http\Controllers\AccountController::class, 'updateNotifications'])->name('update-notifications');
        Route::get('/sessions', [App\Http\Controllers\AccountController::class, 'sessions'])->name('sessions');
        Route::delete('/', [App\Http\Controllers\AccountController::class, 'delete'])->name('delete');
    });

    Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
        
        Route::prefix('boxes')->name('boxes.')->group(function () {
            Route::get('/', [AdminBoxController::class, 'index'])->name('index');
            Route::get('/create', [AdminBoxController::class, 'create'])->name('create');
            Route::post('/', [AdminBoxController::class, 'store'])->name('store');
            Route::get('{box}/edit', [AdminBoxController::class, 'edit'])->name('edit');
            Route::patch('{box}', [AdminBoxController::class, 'update'])->name('update');
            Route::delete('{box}', [AdminBoxController::class, 'destroy'])->name('destroy');
        });
    });
});

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [BoxController::class, 'index'])->name('index');
    Route::get('/{box}', [BoxController::class, 'show'])->name('show');
});

Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripeWebhook']);

