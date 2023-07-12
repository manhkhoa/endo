<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\TransactionRequest;
use App\Http\Resources\Finance\TransactionResource;
use App\Models\Finance\Transaction;
use App\Services\Finance\TransactionListService;
use App\Services\Finance\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function preRequisite(Request $request, TransactionService $service)
    {
        $this->authorize('preRequisite', Transaction::class);

        return response()->ok($service->preRequisite($request));
    }

    public function index(Request $request, TransactionListService $service)
    {
        $this->authorize('viewAny', Transaction::class);

        return $service->paginate($request);
    }

    public function store(TransactionRequest $request, TransactionService $service)
    {
        $this->authorize('create', Transaction::class);

        $transaction = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('finance.transaction.transaction')]),
            'transaction' => TransactionResource::make($transaction),
        ]);
    }

    public function show(string $transaction, TransactionService $service)
    {
        $transaction = Transaction::findIfExists($transaction);

        $this->authorize('view', $transaction);

        $transaction->load('media');

        return TransactionResource::make($transaction);
    }

    public function update(TransactionRequest $request, string $transaction, TransactionService $service)
    {
        $transaction = Transaction::findIfExists($transaction);

        $this->authorize('update', $transaction);

        $service->update($request, $transaction);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('finance.transaction.transaction')]),
        ]);
    }

    public function destroy(string $transaction, TransactionService $service)
    {
        $transaction = Transaction::findIfExists($transaction);

        $this->authorize('delete', $transaction);

        $service->deletable($transaction);

        $transaction->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('finance.transaction.transaction')]),
        ]);
    }
}
