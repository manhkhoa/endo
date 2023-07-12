<?php

namespace App\Services\Finance;

use App\Models\Finance\Ledger;
use App\Models\Finance\Transaction;
use App\Models\Finance\TransactionRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LedgerService
{
    public function preRequisite(Request $request): array
    {
        return [];
    }

    public function create(Request $request): Ledger
    {
        \DB::beginTransaction();

        $ledger = Ledger::forceCreate($this->formatParams($request));

        \DB::commit();

        return $ledger;
    }

    private function formatParams(Request $request, ?Ledger $ledger = null): array
    {
        $formatted = [
            'name' => $request->name,
            'alias' => $request->alias,
            'ledger_type_id' => $request->ledger_type_id,
            'opening_balance' => $request->opening_balance,
            'description' => $request->description,
        ];

        if (! $ledger) {
            $formatted['team_id'] = session('team_id');
        }

        return $formatted;
    }

    private function ensureDoesntHaveTransactions(Ledger $ledger)
    {
        $transactionExists = Transaction::whereLedgerId($ledger->id)->exists();

        $transactionRecordExists = TransactionRecord::whereLedgerId($ledger->id)->exists();

        if ($transactionExists || $transactionRecordExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('finance.ledger.ledger'), 'dependency' => trans('finance.transaction.transaction')])]);
        }
    }

    public function update(Request $request, Ledger $ledger): void
    {
        if ($request->ledger_type_id != $ledger->ledger_type_id) {
            $this->ensureDoesntHaveTransactions($ledger);
        }

        \DB::beginTransaction();

        $ledger->forceFill($this->formatParams($request, $ledger))->save();

        \DB::commit();
    }

    public function deletable(Ledger $ledger): void
    {
        $this->ensureDoesntHaveTransactions($ledger);
    }
}
