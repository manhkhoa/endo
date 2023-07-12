<?php

namespace App\Services\Finance;

use App\Enums\Finance\TransactionType;
use App\Models\Finance\Transaction;
use App\Models\Finance\TransactionRecord;
use App\Support\FormatCodeNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    use FormatCodeNumber;

    private function codeNumber(Request $request)
    {
        $numberPrefix = config('config.finance.'.$request->type.'_number_prefix');
        $numberSuffix = config('config.finance.'.$request->type.'_number_suffix');
        $digit = config('config.finance.'.$request->type.'_number_digit', 0);

        $numberFormat = $numberPrefix.'%NUMBER%'.$numberSuffix;
        $codeNumber = (int) Transaction::byTeam()->whereNumberFormat($numberFormat)->whereType($request->type)->max('number') + 1;

        return $this->getCodeNumber(number: $codeNumber, digit: $digit, format: $numberFormat);
    }

    private function validateCodeNumber(Request $request, string $uuid = null): array
    {
        if (! $request->code_number) {
            return $this->codeNumber($request);
        }

        $duplicateCodeNumber = Transaction::byTeam()->whereCodeNumber($request->code_number)->whereType($request->type)->when($uuid, function ($q, $uuid) {
            $q->where('uuid', '!=', $uuid);
        })->count();

        if ($duplicateCodeNumber) {
            throw ValidationException::withMessages(['message' => trans('global.duplicate', ['attribute' => trans('finance.config.props.'.$request->type.'_number')])]);
        }
    }

    public function preRequisite(Request $request): array
    {
        $types = TransactionType::getOptions();

        return compact('types');
    }

    public function create(Request $request): Transaction
    {
        \DB::beginTransaction();

        $transaction = Transaction::forceCreate($this->formatParams($request));

        $primaryLedger = $request->primary_ledger;
        $primaryLedger->updatePrimaryBalance($request->type, $request->amount);

        $this->updateSecondaryLedger($request, $transaction);

        $transaction->addMedia($request);

        \DB::commit();

        return $transaction;
    }

    private function formatParams(Request $request, ?Transaction $transaction = null): array
    {
        $codeNumberDetail = $this->validateCodeNumber($request, $transaction?->uuid);

        $formatted = [
            'type' => $request->type,
            'date' => $request->date,
            'amount' => $request->amount,
            'ledger_id' => $request->primary_ledger->id,
            'description' => $request->description,
        ];

        if (! $transaction) {
            $formatted['number_format'] = Arr::get($codeNumberDetail, 'number_format');
            $formatted['number'] = Arr::get($codeNumberDetail, 'number');
            $formatted['code_number'] = Arr::get($codeNumberDetail, 'code_number', $request->code_number);
            $formatted['user_id'] = auth()->id();
        }

        return $formatted;
    }

    private function updateSecondaryLedger(Request $request, Transaction $transaction): void
    {
        $transactionRecord = TransactionRecord::with('ledger', 'ledger.type')->whereTransactionId($transaction->id)->first();

        if ($transactionRecord) {
            $previousLedger = $transactionRecord->ledger;
            $previousLedger->reverseSecondaryBalance($transaction->type, $transactionRecord->amount);
        } else {
            $transactionRecord = TransactionRecord::forceCreate([
                'transaction_id' => $transaction->id,
            ]);
        }

        $transactionRecord->ledger_id = $request->secondary_ledger->id;
        $transactionRecord->amount = $request->amount;
        $transactionRecord->description = $request->description;
        $transactionRecord->save();

        $secondaryLedger = $request->secondary_ledger;
        $secondaryLedger->updateSecondaryBalance($request->type, $transactionRecord->amount);
    }

    public function update(Request $request, Transaction $transaction): void
    {
        if ($transaction->type != $request->type) {
            throw ValidationException::withMessages(['message' => trans('finance.transaction.could_not_modify_type')]);
        }

        \DB::beginTransaction();

        $previousLedger = $transaction->ledger;
        $previousLedger->reversePrimaryBalance($transaction->type, $transaction->amount);

        unset($transaction->employee);
        $transaction->forceFill($this->formatParams($request, $transaction))->save();

        $primaryLedger = $request->primary_ledger;
        $primaryLedger->updatePrimaryBalance($request->type, $request->amount);

        $this->updateSecondaryLedger($request, $transaction);

        $transaction->updateMedia($request);

        \DB::commit();
    }

    public function deletable(Transaction $transaction): void
    {
        if (is_null($transaction->cancelled_at)) {
            throw ValidationException::withMessages(['message' => trans('finance.transaction.could_not_perform_if_not_cancelled')]);
        }
    }
}
