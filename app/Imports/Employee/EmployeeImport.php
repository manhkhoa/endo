<?php

namespace App\Imports\Employee;

use App\Concerns\ItemImport;
use App\Enums\Gender;
use App\Helpers\CalHelper;
use App\Helpers\SysHelper;
use App\Models\Company\Branch;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Contact;
use App\Models\Employee\Employee;
use App\Models\Employee\Record as EmployeeRecord;
use App\Models\Option;
use App\Support\FormatCodeNumber;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    use ItemImport, FormatCodeNumber;

    protected $limit = 100;

    public function collection(Collection $rows)
    {
        if (count($rows) > $this->limit) {
            throw ValidationException::withMessages(['message' => trans('general.errors.max_import_limit_crossed', ['attribute' => $this->limit])]);
        }

        $logFile = $this->getLogFile('employee');

        $errors = $this->validate($rows);

        $this->checkForErrors('employee', $errors);

        if (! request()->boolean('validate') && ! \Storage::exists($logFile)) {
            $this->import($rows);
        }
    }

    private function import(Collection $rows)
    {
        activity()->disableLogging();

        \DB::beginTransaction();

        $numberPrefix = config('config.employee.code_number_prefix');
        $numberSuffix = config('config.employee.code_number_suffix');
        $digit = config('config.employee.code_number_digit', 0);

        $numberFormat = $numberPrefix.'%NUMBER%'.$numberSuffix;
        $codeNumber = (int) Employee::byTeam()->whereNumberFormat($numberFormat)->max('number');

        $departments = Department::byTeam()->select('id', 'name')->get();
        $designations = Designation::byTeam()->select('id', 'name')->get();
        $branches = Branch::byTeam()->select('id', 'name')->get();
        $employmentStatuses = Option::byTeam()->whereType('employment_status')->select('id', 'name')->get();

        foreach ($rows as $row) {
            $codeNumber++;
            $codeNumberDetail = $this->getCodeNumber(number: $codeNumber, digit: $digit, format: $numberFormat);

            $contact = Contact::forceCreate([
                'team_id' => session('team_id'),
                'first_name' => Arr::get($row, 'first_name'),
                'middle_name' => Arr::get($row, 'middle_name'),
                'last_name' => Arr::get($row, 'last_name'),
                'gender' => strtolower(Arr::get($row, 'gender')),
                'birth_date' => Date::excelToDateTimeObject(Arr::get($row, 'date_of_birth'))->format('Y-m-d'),
                'contact_number' => Arr::get($row, 'contact_number'),
                'email' => Arr::get($row, 'email'),
                'unique_id_number1' => SysHelper::cleanInput(Arr::get($row, 'unique_id1')),
                'unique_id_number2' => SysHelper::cleanInput(Arr::get($row, 'unique_id2')),
                'unique_id_number3' => SysHelper::cleanInput(Arr::get($row, 'unique_id3')),
                'nationality' => SysHelper::cleanInput(Arr::get($row, 'nationality')),
                'mother_tongue' => SysHelper::cleanInput(Arr::get($row, 'mother_tongue')),
                'birth_place' => SysHelper::cleanInput(Arr::get($row, 'birth_place')),
                'alternate_records' => [
                    'contact_number' => SysHelper::cleanInput(Arr::get($row, 'alternate_contact_number')),
                    'email' => SysHelper::cleanInput(Arr::get($row, 'alternate_email')),
                ],
                'address' => [
                    'present' => [
                        'address_line1' => SysHelper::cleanInput(Arr::get($row, 'address_line1')),
                        'address_line2' => SysHelper::cleanInput(Arr::get($row, 'address_line2')),
                        'city' => SysHelper::cleanInput(Arr::get($row, 'city')),
                        'state' => SysHelper::cleanInput(Arr::get($row, 'state')),
                        'zipcode' => SysHelper::cleanInput(Arr::get($row, 'zipcode')),
                        'country' => SysHelper::cleanInput(Arr::get($row, 'country')),
                    ],
                ],
            ]);

            $employee = Employee::forceCreate([
                'contact_id' => $contact->id,
                'joining_date' => Date::excelToDateTimeObject(Arr::get($row, 'date_of_joining'))->format('Y-m-d'),
                'number_format' => Arr::get($codeNumberDetail, 'number_format'),
                'number' => Arr::get($codeNumberDetail, 'number'),
                'code_number' => Arr::get($codeNumberDetail, 'code_number'),
            ]);

            EmployeeRecord::forceCreate([
                'employee_id' => $employee->id,
                'start_date' => $employee->joining_date,
                'department_id' => $departments->firstWhere('name', Arr::get($row, 'department'))?->id,
                'designation_id' => $designations->firstWhere('name', Arr::get($row, 'designation'))?->id,
                'branch_id' => $branches->firstWhere('name', Arr::get($row, 'branch'))?->id,
                'employment_status_id' => $employmentStatuses->firstWhere('name', Arr::get($row, 'employment_status'))?->id,
            ]);
        }

        \DB::commit();

        activity()->enableLogging();
    }

    private function validate(Collection $rows)
    {
        $departments = Department::byTeam()->pluck('name')->all();
        $designations = Designation::byTeam()->pluck('name')->all();
        $branches = Branch::byTeam()->pluck('name')->all();
        $employmentStatuses = Option::byTeam()->whereType('employment_status')->pluck('name')->all();

        $existingContacts = Contact::byTeam()->get()->pluck('name_with_number')->all();

        $errors = [];

        $newContacts = [];
        foreach ($rows as $index => $row) {
            $rowNo = $index + 2;

            $firstName = Arr::get($row, 'first_name');
            $middleName = Arr::get($row, 'middle_name');
            $lastName = Arr::get($row, 'last_name');
            $gender = Arr::get($row, 'gender');
            $birthDate = Arr::get($row, 'date_of_birth');
            $contactNumber = Arr::get($row, 'contact_number');
            $email = Arr::get($row, 'email');

            $joiningDate = Arr::get($row, 'date_of_joining');
            $department = Arr::get($row, 'department');
            $designation = Arr::get($row, 'designation');
            $branch = Arr::get($row, 'branch');
            $employmentStatus = Arr::get($row, 'employment_status');

            if (! $firstName) {
                $errors[] = $this->setError($rowNo, trans('contact.props.first_name'), 'required');
            } elseif (strlen($firstName) < 2 || strlen($firstName) > 100) {
                $errors[] = $this->setError($rowNo, trans('contact.props.first_name'), 'min_max', ['min' => 2, 'max' => 100]);
            }

            if ($lastName && strlen($lastName) > 100) {
                $errors[] = $this->setError($rowNo, trans('contact.props.last_name'), 'max', ['max' => 100]);
            }

            if ($middleName && strlen($middleName) > 100) {
                $errors[] = $this->setError($rowNo, trans('contact.props.middle_name'), 'max', ['max' => 100]);
            }

            if (! $contactNumber) {
                $errors[] = $this->setError($rowNo, trans('contact.props.contact_number'), 'required');
            } elseif ($contactNumber && strlen($contactNumber) > 20) {
                $errors[] = $this->setError($rowNo, trans('contact.props.contact_number'), 'max', ['max' => 20]);
            }

            if ($email && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = $this->setError($rowNo, trans('contact.props.email'), 'invalid');
            }

            if (! $gender) {
                $errors[] = $this->setError($rowNo, trans('contact.props.gender'), 'required');
            } elseif ($gender && ! in_array(strtolower($gender), Gender::getKeys())) {
                $errors[] = $this->setError($rowNo, trans('contact.props.gender'), 'invalid');
            }

            if (is_int($birthDate)) {
                $birthDate = Date::excelToDateTimeObject($birthDate)->format('Y-m-d');
            }

            if ($birthDate && ! CalHelper::validateDate($birthDate)) {
                $errors[] = $this->setError($rowNo, trans('contact.props.birth_date'), 'invalid');
            }

            if (is_int($joiningDate)) {
                $joiningDate = Date::excelToDateTimeObject($joiningDate)->format('Y-m-d');
            }

            if ($joiningDate && ! CalHelper::validateDate($joiningDate)) {
                $errors[] = $this->setError($rowNo, trans('employee.props.joining_date'), 'invalid');
            }

            if (! $department) {
                $errors[] = $this->setError($rowNo, trans('company.department.department'), 'required');
            } elseif (! in_array($department, $departments)) {
                $errors[] = $this->setError($rowNo, trans('company.department.department'), 'invalid');
            }

            if (! $designation) {
                $errors[] = $this->setError($rowNo, trans('company.designation.designation'), 'required');
            } elseif (! in_array($designation, $designations)) {
                $errors[] = $this->setError($rowNo, trans('company.designation.designation'), 'invalid');
            }

            if (! $branch) {
                $errors[] = $this->setError($rowNo, trans('company.branch.branch'), 'required');
            } elseif (! in_array($branch, $branches)) {
                $errors[] = $this->setError($rowNo, trans('company.branch.branch'), 'invalid');
            }

            if (! $employmentStatus) {
                $errors[] = $this->setError($rowNo, trans('employee.employment_status.employment_status'), 'required');
            } elseif (! in_array($employmentStatus, $employmentStatuses)) {
                $errors[] = $this->setError($rowNo, trans('employee.employment_status.employment_status'), 'invalid');
            }

            $contact = ucwords(preg_replace('/\s+/', ' ', $firstName.' '.$middleName.' '.$lastName)).' '.$contactNumber;

            if (in_array($contact, $existingContacts)) {
                $errors[] = $this->setError($rowNo, trans('employee.employee'), 'exists');
            } elseif (in_array($contact, $newContacts)) {
                $errors[] = $this->setError($rowNo, trans('employee.employee'), 'duplicate');
            }

            $newContacts[] = $contact;
        }

        return $errors;
    }
}
