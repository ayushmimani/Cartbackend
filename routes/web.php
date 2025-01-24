<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('paypal',[PayPalController::class,'payWithPayPal'])->name('payWithPayPal');
// Route::post('paypal-store',[PayPalController::class,'PostPaymentWithPapPal'])->name('postpayment');
// Route::get('status',[PayPalController::class,'getPaymentStatus'])->name('status');

Route::get('paypal', [PayPalController::class, 'index'])->name('paypal');
Route::get('paypal/payment', [PayPalController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PayPalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.payment/cancel');