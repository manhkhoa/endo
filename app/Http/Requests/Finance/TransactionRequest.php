<?php

namespace App\Http\Requests\Finance;

use App\Enums\Finance\TransactionType;
use App\Models\Finance\Ledger;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', new Enum(TransactionType::class)],
            'primary_ledger' => 'required|uuid',
            'secondary_ledger' => 'required|uuid',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|min:2|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('transaction');

            $primaryLedger = Ledger::with('type')->byTeam()->subType('primary')->whereUuid($this->primary_ledger)->getOrFail(trans('finance.ledger.ledger'), 'primary_ledger');

            $secondaryLedgerSubType = $this->type == 'contra' ? 'primary' : 'secondary';

            $secondaryLedger = Ledger::with('type')->byTeam()->subType($secondaryLedgerSubType)->whereUuid($this->secondary_ledger)->getOrFail(trans('finance.ledger.ledger'), 'secondary_ledger');

            if ($primaryLedger->uuid == $secondaryLedger->uuid) {
                $validator->errors()->add('secondary_ledger', trans('validation.different', ['attribute' => __('finance.ledger.ledger'), 'other' => __('finance.ledger.secondary_ledger')]));
            }

            $this->merge([
                'primary_ledger' => $primaryLedger,
                'secondary_ledger' => $secondaryLedger,
            ]);
        });
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'type' => __('finance.transaction.props.type'),
            'primary_ledger' => __('finance.ledger.ledger'),
            'secondary_ledger' => __('finance.ledger.ledger'),
            'date' => __('finance.transaction.props.date'),
            'amount' => __('finance.transaction.props.amount'),
            'description' => __('finance.transaction.props.description'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
