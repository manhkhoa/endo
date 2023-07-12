<?php

use App\Http\Controllers\Calendar\HolidayExportController;
use Illuminate\Support\Facades\Route;

Route::get('calendar/holidays/export', HolidayExportController::class)->middleware('permission:holiday:export');
