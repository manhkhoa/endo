<?php

namespace App\Services\Employee;

use App\Actions\CreateContact;
use App\Actions\UpdateContact;
use App\Enums\Gender;
use App\Models\Contact;
use App\Models\Employee\Employee;
use App\Models\Employee\Record;
use App\Support\FormatCodeNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EmployeeService
{
    use FormatCodeNumber;

    private function codeNumber()
    {
        $numberPrefix = config('config.employee.code_number_prefix');
        $numberSuffix = config('config.employee.code_number_suffix');
        $digit = config('config.employee.code_number_digit', 0);

        $numberFormat = $numberPrefix.'%NUMBER%'.$numberSuffix;
        $codeNumber = (int) Employee::byTeam()->whereNumberFormat($numberFormat)->max('number') + 1;

        return $this->getCodeNumber(number: $codeNumber, digit: $digit, format: $numberFormat);
    }

    private function validateCodeNumber(Request $request, string $uuid = null): array
    {
        $duplicateCodeNumber = Employee::byTeam()->whereCodeNumber($request->code_number)->when($uuid, function ($q, $uuid) {
            $q->where('uuid', '!=', $uuid);
        })->count();

        if ($duplicateCodeNumber) {
            throw ValidationException::withMessages(['code_number' => trans('global.duplicate', ['attribute' => trans('employee.props.code_number')])]);
        }

        $codeNumberDetail = $this->codeNumber();

        return $request->code_number == Arr::get($codeNumberDetail, 'code_number') ? $codeNumberDetail : [];
    }

    public function preRequisite(Request $request): array
    {
        $codeNumber = Arr::get($this->codeNumber(), 'code_number');

        $genders = Gender::getOptions();

        $employeeTypes = [
            ['label' => trans('global.new', ['attribute' => trans('employee.employee')]), 'value' => 'new'],
            ['label' => trans('global.existing', ['attribute' => trans('employee.employee')]), 'value' => 'existing'],
        ];

        return compact('codeNumber', 'genders', 'employeeTypes');
    }

    public function create(Request $request): Employee
    {
        \DB::beginTransaction();

        if ($request->employee_type == 'new') {
            $contact = (new CreateContact)->execute($request->all());

            $request->merge([
                'contact_id' => $contact->id,
            ]);
        }

        $employee = Employee::forceCreate($this->formatParams($request));

        $employeeRecord = Record::forceCreate([
            'employee_id' => $employee->id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'branch_id' => $request->branch_id,
            'employment_status_id' => $request->employment_status_id,
            'start_date' => $request->joining_date,
        ]);

        \DB::commit();

        return $employee;
    }

    private function formatParams(Request $request, ?Employee $employee = null): array
    {
        $codeNumberDetail = $this->validateCodeNumber($request);

        $formatted = [
            'contact_id' => $request->contact_id,
            'joining_date' => $request->joining_date,
            'number_format' => Arr::get($codeNumberDetail, 'number_format'),
            'number' => Arr::get($codeNumberDetail, 'number'),
            'code_number' => $request->code_number,
        ];

        return $formatted;
    }

    public function update(Request $request, Employee $employee): void
    {
        $contact = $employee->contact;

        $existingContact = Contact::byTeam()->where('uuid', '!=', $contact->uuid)
            ->whereFirstName($request->input('first_name', $contact->first_name))
            ->whereMiddleName($request->input('middle_name', $contact->middle_name))
            ->whereThirdName($request->input('third_name', $contact->third_name))
            ->whereLastName($request->input('last_name', $contact->last_name))
            ->whereContactNumber($request->input('contact_number', $contact->contact_number))
            ->count();

        if ($existingContact) {
            throw ValidationException::withMessages(['message' => trans('employee.exists')]);
        }

        \DB::beginTransaction();

        (new UpdateContact)->execute($request, $employee->contact);

        \DB::commit();
    }

    public function deletable(Employee $employee): void
    {
    }
}
