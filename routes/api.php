<?php

use App\Http\Controllers\PayrollCalculationController;
use App\Http\Controllers\PayrollLocationsController;
use Illuminate\Support\Facades\Route;

Route::post('/payroll/calculate', PayrollCalculationController::class)->name('payroll.calculate');
Route::get('/locations', PayrollLocationsController::class)->name('payroll.locations');
