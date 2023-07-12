<?php

namespace App\Http\Requests\Employee;

use App\Enums\Gender;
use App\Helpers\CalHelper;
use App\Models\Company\Branch;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Contact;
use App\Models\Employee\Employee;
use App\Models\Option;
use App\Rules\AlphaSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class EmployeeRequest extends FormRequest
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
            'employee_type' => 'required|in:new,existing',
            'joining_date' => 'required|date',
            'department' => 'required',
            'designation' => 'required',
            'branch' => 'required',
            'employment_status' => 'required',
            'code_number' => 'required|max:50',
        ];

        if ($this->employee_type == 'new') {
            $rules['first_name'] = ['required', 'min:2', 'max:100', new AlphaSpace];
            $rules['last_name'] = ['max:100', new AlphaSpace];
            $rules['gender'] = ['required', new Enum(Gender::class)];
            $rules['birth_date'] = 'required|date';
            $rules['contact_number'] = 'required|max:20';
        } else {
            $rules['employee'] = 'required';
        }

        if (config('config.employee.enable_middle_name_field')) {
            $rules['middle_name'] = ['nullable', 'max:100', new AlphaSpace];
        }

        if (config('config.employee.enable_third_name_field')) {
            $rules['third_name'] = ['nullable', 'max:100', new AlphaSpace];
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        if (! $validator->passes()) {
            return;
        }

        $validator->after(function ($validator) {
            $department = Department::byTeam()->whereUuid($this->department)->getOrFail(__('company.department.department'), 'department');

            $designation = Designation::byTeam()->whereUuid($this->designation)->getOrFail(__('company.designation.designation'), 'designation');

            $branch = Branch::byTeam()->whereUuid($this->branch)->getOrFail(__('company.branch.branch'), 'branch');

            $employmentStatus = Option::byTeam()->whereType('employment_status')->whereUuid($this->employment_status)->getOrFail(__('employee.employment_status.employment_status'), 'employment_status');

            if ($this->employee_type == 'existing') {
                $contact = Contact::byTeam()->whereHas('employees', function ($q) {
                    $q->whereUuid($this->employee);
                })->first();

                if (! $contact) {
                    throw ValidationException::withMessages(['message' => trans('global.could_not_find', ['attribute' => trans('employee.employee')])]);
                }

                $this->merge([
                    'contact_id' => $contact->id,
                ]);

                $existingEmployee = Employee::whereContactId($contact->id)
                    ->whereNull('leaving_date')
                    ->count();

                if ($existingEmployee) {
                    $validator->errors()->add('message', trans('employee.exists'));
                }

                $overlappingDate = Employee::whereContactId($contact->id)->whereNotNull('leaving_date')->orderBy('leaving_date', 'desc')->first();

                if ($overlappingDate && $overlappingDate->leaving_date >= $this->joining_date) {
                    $validator->errors()->add('message', trans('employee.joining_date_less_than_leaving_date', ['attribute' => CalHelper::showDate($overlappingDate->leaving_date)]));
                }
            }

            $this->merge([
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'branch_id' => $branch->id,
                'employment_status_id' => $employmentStatus->id,
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
            'department' => __('company.department.department'),
            'designation' => __('company.designation.designation'),
            'joining_date' => __('employee.props.joining_date'),
            'code_number' => __('employee.props.code_number'),
            'first_name' => __('contact.props.first_name'),
            'middle_name' => __('contact.props.middle_name'),
            'third_name' => __('contact.props.third_name'),
            'last_name' => __('contact.props.last_name'),
            'gender' => __('contact.props.gender'),
            'birth_date' => __('contact.props.birth_date'),
            'contact_number' => __('contact.props.contact_number'),
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
