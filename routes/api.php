<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\employeeController;


Route::get('/employee', [employeeController::class, 'index']);

Route::get('/employee/{id}', [employeeController::class, 'show']);

Route::post('/employee', [employeeController::class, 'store']);