<?php

use App\Http\Controllers\PayrollCalculationController;
use Illuminate\Support\Facades\Route;

Route::post('/payroll/calculate', PayrollCalculationController::class);
