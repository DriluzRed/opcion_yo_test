<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;

Route::get('/available-employees', [AvailabilityController::class, 'getAvailableEmployees']);
Route::post('/check-availability', [AvailabilityController::class, 'checkAvailability']);


