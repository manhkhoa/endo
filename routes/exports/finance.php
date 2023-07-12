<?php

use App\Http\Controllers\Finance\LedgerExportController;
use App\Http\Controllers\Finance\LedgerTypeExportController;
use App\Http\Controllers\Finance\TransactionExportController;
use Illuminate\Support\Facades\Route;

Route::get('finance/ledger-types/export', LedgerTypeExportController::class)->middleware('permission:finance:config');
Route::get('finance/ledgers/export', LedgerExportController::class)->middleware('permission:ledger:export');
Route::get('finance/transactions/export', TransactionExportController::class)->middleware('permission:transaction:export');
