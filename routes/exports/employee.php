<?php

use App\Http\Controllers\Employee\AccountController;
use App\Http\Controllers\Employee\AccountExportController;
use App\Http\Controllers\Employee\DocumentController;
use App\Http\Controllers\Employee\DocumentExportController;
use App\Http\Controllers\Employee\EmployeeExportController;
use App\Http\Controllers\Employee\ExperienceController;
use App\Http\Controllers\Employee\ExperienceExportController;
use App\Http\Controllers\Employee\QualificationController;
use App\Http\Controllers\Employee\QualificationExportController;
use App\Http\Controllers\Employee\RecordController;
use App\Http\Controllers\Employee\RecordExportController;
use Illuminate\Support\Facades\Route;

Route::get('employees/{employee}/records/{record}/media/{uuid}', [RecordController::class, 'downloadMedia']);
Route::get('employees/{employee}/qualifications/{qualification}/media/{uuid}', [QualificationController::class, 'downloadMedia']);
Route::get('employees/{employee}/accounts/{account}/media/{uuid}', [AccountController::class, 'downloadMedia']);
Route::get('employees/{employee}/documents/{document}/media/{uuid}', [DocumentController::class, 'downloadMedia']);
Route::get('employees/{employee}/experiences/{experience}/media/{uuid}', [ExperienceController::class, 'downloadMedia']);

Route::get('employees/{employee}/records/export', RecordExportController::class)->middleware('permission:employment-record:manage');
Route::get('employees/{employee}/qualifications/export', QualificationExportController::class)->middleware('permission:employee:export');
Route::get('employees/{employee}/accounts/export', AccountExportController::class)->middleware('permission:employee:export');
Route::get('employees/{employee}/documents/export', DocumentExportController::class)->middleware('permission:employee:export');
Route::get('employees/{employee}/experiences/export', ExperienceExportController::class)->middleware('permission:employee:export');

Route::get('employees/export', EmployeeExportController::class)->middleware('permission:employee:export');
