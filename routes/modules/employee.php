<?php

use App\Http\Controllers\Employee\AccountController;
use App\Http\Controllers\Employee\DocumentController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeImportController;
use App\Http\Controllers\Employee\ExperienceController;
use App\Http\Controllers\Employee\PhotoController;
use App\Http\Controllers\Employee\QualificationController;
use App\Http\Controllers\Employee\RecordController;
use App\Http\Controllers\Employee\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:employee:read')->group(function () {
    Route::post('employees/{employee}/user/confirm', [UserController::class, 'confirm'])->name('employees.confirmUser');
    Route::get('employees/{employee}/user', [UserController::class, 'index'])->name('employees.getUser');
    Route::post('employees/{employee}/user', [UserController::class, 'create'])->name('employees.createUser');
    Route::patch('employees/{employee}/user', [UserController::class, 'update'])->name('employees.updateUser');

    Route::post('employees/{employee}/photo', [PhotoController::class, 'upload'])
        ->name('employees.uploadPhoto');

    Route::delete('employees/{employee}/photo', [PhotoController::class, 'remove'])
        ->name('employees.removePhoto');

    Route::apiResource('employees.records', RecordController::class);
    Route::apiResource('employees.qualifications', QualificationController::class);
    Route::apiResource('employees.accounts', AccountController::class);
    Route::apiResource('employees.documents', DocumentController::class);
    Route::apiResource('employees.experiences', ExperienceController::class);

    Route::get('employees/pre-requisite', [EmployeeController::class, 'preRequisite'])->name('employees.preRequisite');
    Route::post('employees/import', EmployeeImportController::class)->name('employees.import')->middleware('permission:employees:create');
    Route::apiResource('employees', EmployeeController::class);
});
