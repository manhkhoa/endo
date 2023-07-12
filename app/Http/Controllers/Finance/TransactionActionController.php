<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Transaction;
use App\Services\Finance\TransactionActionService;
use Illuminate\Http\Request;

class TransactionActionController extends Controller
{
    public function cancel(Request $request, string $transaction, TransactionActionService $service)
    {
        $transaction = Transaction::findIfExists($transaction);

        $this->authorize('cancel', $transaction);

        $service->cancel($request, $transaction);

        return response()->success([
            'message' => trans('global.cancelled', ['attribute' => trans('finance.transaction.transaction')]),
        ]);
    }
}
