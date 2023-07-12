<?php

namespace App\Services\Finance;

use App\Models\Finance\Transaction;
use App\Models\Finance\TransactionRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionActionService
{
    public function cancel(Request $request, Transaction $transaction)
    {
        if ($transaction->cancelled_at) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_operation')]);
        }

        $ledger = $transaction->ledger;
        $ledger->reversePrimaryBalance($transaction->type, $transaction->amount);

        $transactionRecord = TransactionRecord::with('ledger', 'ledger.type')->whereTransactionId($transaction->id)->first();

        if ($transactionRecord) {
            $secondaryLedger = $transactionRecord->ledger;
            $secondaryLedger->reverseSecondaryBalance($transaction->type, $transactionRecord->amount);
        }

        unset($transaction->employee);
        $transaction->cancelled_at = now();
        $transaction->save();
    }
}
