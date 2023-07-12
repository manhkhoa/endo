<?php

use App\Http\Controllers\Company\BranchController;
use App\Http\Controllers\Company\BranchImportController;
use App\Http\Controllers\Company\DepartmentController;
use App\Http\Controllers\Company\DepartmentImportController;
use App\Http\Controllers\Company\DesignationController;
use App\Http\Controllers\Company\DesignationImportController;
use Illuminate\Support\Facades\Route;

// Company Routes
Route::prefix('company')->group(function () {
    Route::post('departments/import', DepartmentImportController::class)->middleware('permission:department:create');
    Route::apiResource('departments', DepartmentController::class);

    Route::post('designations/import', DesignationImportController::class)->middleware('permission:designation:create');
    Route::apiResource('designations', DesignationController::class);

    Route::post('branches/import', BranchImportController::class)->middleware('permission:branch:create');
    Route::apiResource('branches', BranchController::class);
});
