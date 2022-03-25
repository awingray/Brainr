<?php

use Illuminate\Support\Facades\Route;

Route::get('{url}', 'SPAController@index')
     ->where('url', '.*');

// Authentication Routes...
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')
     ->name('logout');

// Registration Routes...
if (config('auth.allow_register')) {
    Route::post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
if (config('auth.allow_reset')) {
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
         ->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')
         ->name('password.update');
}

// Password Confirmation Routes...
if (config('auth.confirm_password')) {
    Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');
}

// Email Verification Routes...
if (config('auth.confirm_email')) {
    Route::post('email/resend', 'Auth\VerificationController@resend')
         ->name('verification.resend');
}
