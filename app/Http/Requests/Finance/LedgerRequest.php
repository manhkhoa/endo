<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\Ledger;
use App\Models\Finance\LedgerType;
use Illuminate\Foundation\Http\FormRequest;

class LedgerRequest extends FormRequest
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
            'name' => 'required|min:2|max:100',
            'alias' => 'nullable|min:2|max:100',
            'ledger_type' => 'required|uuid',
            'opening_balance' => 'required|numeric',
            'description' => 'nullable|min:2|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('ledger.uuid');

            $ledgerType = LedgerType::query()
                ->byTeam()
                ->whereUuid($this->ledger_type)
                ->getOrFail(trans('finance.ledger_type.ledger_type'), 'ledger_type');

            $existingNames = Ledger::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereName($this->name)
                ->exists();

            if ($existingNames) {
                $validator->errors()->add('name', trans('validation.unique', ['attribute' => __('finance.ledger.ledger')]));
            }

            $this->merge([
                'ledger_type_id' => $ledgerType->id,
            ]);

            if (! $this->alias) {
                return;
            }

            $existingAliases = Ledger::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereAlias($this->alias)
                ->exists();

            if ($existingAliases) {
                $validator->errors()->add('alias', trans('validation.unique', ['attribute' => __('finance.ledger.ledger')]));
            }
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
            'name' => trans('finance.ledger.props.name'),
            'alias' => trans('finance.ledger.props.alias'),
            'ledger_type' => trans('finance.ledger_type.ledger_type'),
            'opening_balance' => trans('finance.ledger.props.opening_balance'),
            'description' => trans('finance.ledger.props.description'),
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
