<?php

use App\Http\Controllers\Finance\LedgerController;
use App\Http\Controllers\Finance\LedgerTypeController;
use App\Http\Controllers\Finance\TransactionActionController;
use App\Http\Controllers\Finance\TransactionController;
use Illuminate\Support\Facades\Route;

// Finance Routes
Route::prefix('finance')->group(function () {
    Route::get('ledger-types/pre-requisite', [LedgerTypeController::class, 'preRequisite'])->name('ledgerTypes.preRequisite')->middleware('permission:finance:config');
    Route::apiResource('ledger-types', LedgerTypeController::class)->names('ledgerTypes')->middleware('permission:finance:config');

    Route::get('ledgers/pre-requisite', [LedgerController::class, 'preRequisite'])->name('ledgers.preRequisite');
    Route::apiResource('ledgers', LedgerController::class)->names('ledgers');

    Route::get('transactions/pre-requisite', [TransactionController::class, 'preRequisite'])->name('transactions.preRequisite');
    Route::post('transactions/{transaction}/cancel', [TransactionActionController::class, 'cancel'])->name('transactions.cancel');
    Route::apiResource('transactions', TransactionController::class)->names('transactions');
});
