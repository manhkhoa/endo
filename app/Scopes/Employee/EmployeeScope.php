<?php

namespace App\Scopes\Employee;

use App\Helpers\CalHelper;
use App\Models\Employee\Record;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait EmployeeScope
{
    public function scopeDetail(Builder $query)
    {
        $query->select(
            'employees.id', 'employees.uuid', 'employees.code_number', 'employees.joining_date', 'employees.leaving_date', 'photo',
            'employees.created_at', 'employees.contact_id', 'employees.meta',
            'employee_records.start_date', 'employee_records.end_date', 'employee_records.id as last_record_id',
            (app()->environment('testing') ?
            'contacts.first_name as full_name' :
            \DB::raw('concat_ws(" ",contacts.first_name,contacts.middle_name,contacts.third_name,contacts.last_name) as full_name')),
            'contacts.birth_date', 'contacts.anniversary_date', 'contacts.gender', 'contacts.user_id', 'contacts.team_id',
            'departments.name as department_name', 'departments.uuid as department_uuid', 'departments.id as department_id',
            'designations.name as designation_name', 'designations.uuid as designation_uuid', 'designations.id as designation_id',
            'branches.name as branch_name', 'branches.uuid as branch_uuid', 'branches.id as branch_id',
            'options.name as employment_status_name', 'options.uuid as employment_status_uuid', 'options.id as employment_status_id',
        );
    }

    public function scopeFilterRecord(Builder $query, string $date = null)
    {
        if (! CalHelper::validateDate($date)) {
            $date = today()->toDateString();
        }

        $query->leftJoin('employee_records', function ($join) use ($date) {
            $join->on('employees.id', '=', 'employee_records.employee_id')
                ->on('start_date', '=', \DB::raw("(select start_date from employee_records where employees.id = employee_records.employee_id and start_date <= '".$date."' order by start_date desc limit 1)"))
                ->join('departments', 'employee_records.department_id', '=', 'departments.id')
                ->join('designations', 'employee_records.designation_id', '=', 'designations.id')
                ->join('branches', 'employee_records.branch_id', '=', 'branches.id')
                ->join('options', 'employee_records.employment_status_id', '=', 'options.id');
        })->join('contacts', function ($join) {
            $join->on('employees.contact_id', '=', 'contacts.id')
                ->where('contacts.team_id', session('team_id'));
        })->ignoreDefaultEmployee();
    }

    public function scopeWithDetail(Builder $query)
    {
        $query->detail()->filterRecord();
    }

    public function scopeFindWithDetailOrFail(Builder $query, string $uuid, $field = 'message')
    {
        return $query
            ->detail()
            ->filterRecord()
            ->where('employees.uuid', $uuid)
            ->getOrFail(trans('employee.employee'), $field);
    }

    public function scopeGetWithDetailOrFail(Builder $query, array $uuids, $field = 'message')
    {
        return $query
            ->detail()
            ->filterRecord()
            ->whereIn('employees.uuid', $uuids)
            ->getAllOrFail(trans('employee.employee'), $field);
    }

    public function scopeAuth(Builder $query, ?int $userId = null)
    {
        if (! auth()->check()) {
            return;
        }

        if (! $userId) {
            $userId = auth()->id();
        }

        $query->select('employees.id', 'employees.uuid', 'contacts.user_id')
        ->join('contacts', function ($join) use ($userId) {
            $join->on('employees.contact_id', '=', 'contacts.id')
                ->where('contacts.team_id', session('team_id'))
                ->where('contacts.user_id', $userId);
        });
    }

    public function scopeIgnoreDefaultEmployee(Builder $query)
    {
        if (! auth()->check()) {
            return;
        }

        $query->when(! auth()->user()->is_default, function ($q) {
            $q->where(function ($q) {
                $q->where('employees.meta->is_default', '!=', true)->orWhere('employees.meta->is_default', null);
            });
        });
    }

    public function scopeWithAuthRecordId(Builder $query, string|array $records = ['designation', 'branch'], ?int $userId = null)
    {
        if (is_string($records)) {
            $records = [$records];
        }

        $query->auth($userId);

        if (in_array('designation', $records)) {
            $query->currentRecordId('designation');
        }

        if (in_array('branch', $records)) {
            $query->currentRecordId('branch');
        }

        return $query->getOrFail(trans('employee.employee'));
    }

    public function scopeWithUserRecordId(Builder $query, ?int $userId = null)
    {
        $query->auth($userId)
            ->currentRecordId('designation')
            ->currentRecordId('branch')
            ->getOrFail(trans('employee.employee'));
    }

    public function scopeIgnoreSelf(Builder $query, bool $self = true, int $id = 0)
    {
        $query->when(! $self, function ($q) use ($id) {
            $q->where('employees.id', '!=', $id);
        });
    }

    public function scopeSummary(Builder $query, bool $ignoreDefault = true)
    {
        $query->select(
            'employees.id', 'employees.uuid', 'employees.code_number', 'employees.joining_date', 'employees.leaving_date', 'photo',
            (app()->environment('testing') ?
            'contacts.first_name as full_name' :
            \DB::raw('concat_ws(" ",contacts.first_name,contacts.middle_name,contacts.third_name,contacts.last_name) as full_name')), 'contacts.id as contact_id', 'contacts.team_id', 'contacts.gender', 'contacts.birth_date', 'contacts.user_id'
        )
            ->join('contacts', function ($join) {
                $join->on('employees.contact_id', '=', 'contacts.id')
                    ->where('contacts.team_id', session('team_id'));
            })->when($ignoreDefault, function ($q) {
                $q->ignoreDefaultEmployee();
            });
    }

    public function scopeFilterDesignation(Builder $query, bool|array $designations = [], int $id = null)
    {
        if (auth()->user()->is_default) {
            return;
        }

        if (auth()->user()->can('designation:admin-access')) {
            return;
        }

        if (! is_array($designations)) {
            return;
        }

        $query->where(function ($q) use ($designations, $id) {
            $q->whereIn('designations.id', $designations)->orWhere('employees.id', $id);
        });
    }

    public function scopeFilterBranch(Builder $query, bool|array $branches = [], int $id = null)
    {
        if (auth()->user()->is_default) {
            return;
        }

        if (auth()->user()->can('branch:admin-access')) {
            return;
        }

        if (! is_array($branches)) {
            return;
        }

        $query->where(function ($q) use ($branches, $id) {
            $q->whereIn('branches.id', $branches)->orWhere('employees.id', $id);
        });
    }

    public function scopeWithSummaryRecordId(Builder $query, bool $ignoreDefault = true)
    {
        $query
            ->summary($ignoreDefault)
            ->currentRecordId('designation')
            ->currentRecordId('branch');
    }

    public function scopeWithSummaryRecord(Builder $query, bool $ignoreDefault = true)
    {
        $query
            ->summary($ignoreDefault)
            ->currentRecord('designation')
            ->currentRecord('branch');
    }

    public function scopeWithFullRecordDetail(Builder $query)
    {
        $query
            ->summary()
            ->currentRecordId('designation')
            ->currentRecordId('branch')
            ->currentRecord('designation')
            ->currentRecord('branch')
            ->currentRecord('department')
            ->currentRecord('employment_status');
    }

    public function scopeWithFullRecordId(Builder $query)
    {
        $query
            ->summary()
            ->currentRecordId('designation')
            ->currentRecordId('branch')
            ->currentRecordId('department')
            ->currentRecordId('employment_status');
    }

    public function scopeWithFullRecord(Builder $query)
    {
        $query
            ->summary()
            ->currentRecord('designation')
            ->currentRecord('branch')
            ->currentRecord('department')
            ->currentRecord('employment_status');
    }

    public function scopeFindWithSummaryOrFail(Builder $query, string $uuid)
    {
        return $query
            ->withSummaryRecordId()
            ->where('employees.uuid', $uuid)
            ->getOrFail(trans('employee.employee'));
    }

    public function scopeCurrentRecordId(Builder $query, $type = 'designation')
    {
        $query->addSelect([
            'current_'.$type.'_id' => Record::select($type.'_id')
                ->whereColumn('employee_id', 'employees.id')
                ->where('start_date', '<=', today()->toDateString())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere(function ($q) {
                        $q->whereNotNull('end_date')->where('end_date', '>=', today()->toDateString());
                    });
                })
                ->orderBy('start_date', 'desc')
                ->limit(1),
        ]);
    }

    public function scopeCurrentRecord(Builder $query, $type = 'designation')
    {
        $field = 'current_'.$type.'_name';

        if ($type == 'employment_status') {
            $type = 'option';
        }

        $select = Str::plural($type).'.name';

        $query->addSelect([
            $field => Record::select($select)
                ->when($type == 'branch', function ($q) {
                    $q->join('branches', 'employee_records.branch_id', '=', 'branches.id');
                })
                ->when($type == 'designation', function ($q) {
                    $q->join('designations', 'employee_records.designation_id', '=', 'designations.id');
                })
                ->when($type == 'department', function ($q) {
                    $q->join('departments', 'employee_records.department_id', '=', 'departments.id');
                })
                ->when($type == 'option', function ($q) {
                    $q->join('options', 'employee_records.employment_status_id', '=', 'options.id');
                })
                ->whereColumn('employee_id', 'employees.id')
                ->where('start_date', '<=', today()->toDateString())
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere(function ($q) {
                        $q->whereNotNull('end_date')->where('end_date', '>=', today()->toDateString());
                    });
                })
                ->orderBy('start_date', 'desc')
                ->limit(1),
        ]);
    }

    public function scopeWithLastRecord(Builder $query)
    {
        $query->addSelect(['last_record_id' => Record::select('id')
            ->whereColumn('employee_id', 'employees.id')
            ->where('start_date', '<=', today()->toDateString())
            ->orderBy('start_date', 'desc')
            ->limit(1),
        ])->with('lastRecord');
    }
}
