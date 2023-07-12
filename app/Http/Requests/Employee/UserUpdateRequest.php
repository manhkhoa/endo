<?php

namespace App\Http\Requests\Employee;

use App\Models\Team\Role;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'roles' => 'required|array|min:1',
        ];

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $allowedRoles = Role::selectOption();

            if (array_diff($this->roles, $allowedRoles->pluck('uuid')->all())) {
                $validator->errors()->add('roles', trans('general.errors.invalid_input'));
            }

            $this->merge(['role_ids' => $allowedRoles->whereIn('uuid', $this->roles)->pluck('id')->all()]);
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
            'role' => __('contact.login.props.role'),
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
