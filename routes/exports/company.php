<?php

use App\Http\Controllers\Company\BranchExportController;
use App\Http\Controllers\Company\DepartmentExportController;
use App\Http\Controllers\Company\DesignationExportController;
use Illuminate\Support\Facades\Route;

Route::get('company/departments/export', DepartmentExportController::class)->middleware('permission:department:export');
Route::get('company/designations/export', DesignationExportController::class)->middleware('permission:designation:export');
Route::get('company/branches/export', BranchExportController::class)->middleware('permission:branch:export');
