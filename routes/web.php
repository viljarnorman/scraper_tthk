<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyViewController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;

Route::get('/companies', [CompanyViewController::class, 'companies'])->middleware(['auth'])->name('companies');
Route::get('/people', [PersonController::class, 'index'])->name('people');

Route::redirect('/', '/companies');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['middleware' => function ($request, $next) {
        if (Auth::user() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        abort(403, 'Access denied');
    }], function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
