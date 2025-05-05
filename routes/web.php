<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyViewController;
use App\Http\Controllers\PersonController;
use App\Models\Person;
use Illuminate\Support\Arr;

Route::get('/companies', [CompanyViewController::class, 'companies'])->name('companies');
Route::get('/people', [PersonController::class, 'index'])->name('people');

    

