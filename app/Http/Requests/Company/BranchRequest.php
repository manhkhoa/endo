<?php

namespace App\Http\Requests\Company;

use App\Models\Company\Branch;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'code' => 'required|min:2|max:20',
            'alias' => 'nullable|min:2|max:100',
            'parent' => 'nullable',
            'description' => 'nullable|min:2|max:1000',
        ];
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $uuid = $this->route('branch.uuid');

            $existingNames = Branch::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereName($this->name)
                ->count();

            if ($existingNames) {
                $validator->errors()->add('name', trans('validation.unique', ['attribute' => __('company.branch.branch')]));
            }

            $existingCode = Branch::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereCode($this->code)
                ->count();

            if ($existingCode) {
                $validator->errors()->add('code', trans('validation.unique', ['attribute' => __('company.branch.branch')]));
            }

            if ($this->parent) {
                $parentBranch = Branch::query()
                    ->byTeam()
                    ->whereUuid($this->parent)
                    ->getOrFail(__('company.branch.branch'), 'parent');

                $this->merge(['branch_id' => $parentBranch->id]);
            }

            if (! $this->alias) {
                return;
            }

            $existingAliases = Branch::query()
                ->byTeam()
                ->when($uuid, function ($q, $uuid) {
                    $q->where('uuid', '!=', $uuid);
                })
                ->whereAlias($this->alias)
                ->count();

            if ($existingAliases) {
                $validator->errors()->add('alias', trans('validation.unique', ['attribute' => __('company.branch.branch')]));
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
            'name' => __('company.branch.props.name'),
            'code' => __('company.branch.props.code'),
            'alias' => __('company.branch.props.alias'),
            'parent' => __('company.branch.props.parent'),
            'description' => __('company.branch.props.description'),
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
