<?php

namespace App\Http\Requests\Finance;

use App\Enums\Finance\LedgerGroup;
use App\Models\Finance\LedgerType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class LedgerTypeRequest extends FormRequest
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
        $rules = [
            'name' => 'required|min:2|max:100',
            'alias' => 'nullable|min:2|max:100',
            'parent' => 'nullable',
            'description' => 'nullable|min:2|max:1000',
        ];

        if (! $this->parent) {
            $rules['type'] = ['required', new Enum(LedgerGroup::class)];
        } else {
            $rules['type'] = 'nullable';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $this->merge([
            'has_account' => LedgerGroup::hasAccount($this->type),
            'has_contact' => LedgerGroup::hasContact($this->type),
        ]);

        $validator->after(function ($validator) {
            $uuid = $this->route('ledger_type.uuid');

            $existingNames = LedgerType::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereName($this->name)
                ->exists();

            if ($existingNames) {
                $validator->errors()->add('name', trans('validation.unique', ['attribute' => __('finance.ledger_type.ledger_type')]));
            }

            if ($this->parent) {
                $parentLedgerType = LedgerType::query()
                    ->byTeam()
                    ->whereUuid($this->parent)
                    ->getOrFail(__('finance.ledger_type.ledger_type'), 'parent');

                $this->merge(['parent_id' => $parentLedgerType->id, 'parent_ledger_type' => $parentLedgerType->type]);
            }

            if (! $this->alias) {
                return;
            }

            $existingAliases = LedgerType::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereAlias($this->alias)
                ->count();

            if ($existingAliases) {
                $validator->errors()->add('alias', trans('validation.unique', ['attribute' => __('finance.ledger_type.ledger_type')]));
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
            'name' => __('finance.ledger_type.props.name'),
            'alias' => __('finance.ledger_type.props.alias'),
            'type' => __('finance.ledger_type.props.type'),
            'parent' => __('finance.ledger_type.props.parent'),
            'description' => __('finance.ledger_type.props.description'),
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
