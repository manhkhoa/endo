<?php

use App\Http\Controllers\Calendar\HolidayController;
use Illuminate\Support\Facades\Route;

// Calendar Routes
Route::prefix('calendar')->group(function () {
    Route::apiResource('holidays', HolidayController::class);
});
