<?php

namespace App\Concerns;

use App\Helpers\CalHelper;
use App\Models\Company\Branch;
use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

trait SubordinateAccess
{
    public function getAccessibleDesignation(): bool|array
    {
        if (auth()->user()->is_default) {
            return true;
        }

        if (auth()->user()->can('designation:admin-access')) {
            return true;
        }

        $employee = Employee::withAuthRecordId('designation');

        return $this->accessibleDesignationRules($employee);
    }

    public function accessibleDesignationRules(Employee $employee): bool|array
    {
        $designationIds = [];
        if (auth()->user()->can('designation:self-access')) {
            array_push($designationIds, $employee->current_designation_id);
        }

        if (! auth()->user()->can('designation:subordinate-access')) {
            return $designationIds;
        }

        $designations = Designation::byTeam()->whereNotNull('parent_id')->pluck('parent_id', 'id')->all();

        $employeeDesignation = $employee->current_designation_id ?? $employee->lastRecord?->designation_id;

        $childDesignationIds = $this->getChilds($designations, $employeeDesignation);

        return array_merge($designationIds, $childDesignationIds);
    }

    public function getAccessibleBranch(): bool|array
    {
        if (auth()->user()->is_default) {
            return true;
        }

        if (auth()->user()->can('branch:admin-access')) {
            return true;
        }

        $employee = Employee::withAuthRecordId('branch');

        return $this->accessibleBranchRules($employee);
    }

    public function accessibleBranchRules(Employee $employee): bool|array
    {
        $branchIds = [];
        if (auth()->user()->can('branch:self-access')) {
            array_push($branchIds, $employee->current_branch_id);
        }

        if (! auth()->user()->can('branch:subordinate-access')) {
            return $branchIds;
        }

        $branches = Branch::byTeam()->whereNotNull('parent_id')->pluck('parent_id', 'id')->all();

        $employeeBranch = $employee->current_branch_id ?? $employee->lastRecord?->branch_id;

        $childBranchIds = $this->getChilds($branches, $employeeBranch);

        return array_merge($branchIds, $childBranchIds);
    }

    public function getAccessibleEmployee(bool $self = true, string $date = null)
    {
        if (! $date) {
            $date = today()->toDateString();
        }

        $employee = Employee::withAuthRecordId();
        $accessibleBranch = $this->accessibleBranchRules($employee);
        $accessibleDesignation = $this->accessibleDesignationRules($employee);

        $query = Employee::select('employees.id')
            ->filterRecord($date)
            ->ignoreSelf($self, $employee->id)
            ->filterDesignation($accessibleDesignation, $employee->id)
            ->filterBranch($accessibleBranch, $employee->id);

        return $query->get()->pluck('id')->all();
    }

    public function isAccessibleEmployee(Employee $employee, bool $self = true): bool
    {
        if (auth()->user()->is_default || auth()->user()->hasRole('admin')) {
            return true;
        }

        if ($employee->is_default) {
            return false;
        }

        $me = Employee::withAuthRecordId();
        $accessibleBranch = $this->accessibleBranchRules($me);
        $accessibleDesignation = $this->accessibleDesignationRules($me);

        if (! $self && $me->id == $employee->id) {
            return false;
        }

        if (is_bool($accessibleDesignation) && is_bool($accessibleBranch)) {
            return $accessibleDesignation && $accessibleBranch;
        }

        $employeeDesignation = $employee->current_designation_id ?? $employee->lastRecord?->designation_id;
        $employeeBranch = $employee->current_branch_id ?? $employee->lastRecord?->branch_id;

        if (! in_array($employeeDesignation, $accessibleDesignation)) {
            return false;
        }

        if (! in_array($employeeBranch, $accessibleBranch)) {
            return false;
        }

        return true;
    }

    public function areAccessibleEmployees(Collection $employees): bool
    {
        if (auth()->user()->is_default || auth()->user()->hasRole('admin')) {
            return true;
        }

        $me = Employee::withAuthRecordId();
        $accessibleBranch = $this->accessibleBranchRules($me);
        $accessibleDesignation = $this->accessibleDesignationRules($me);

        if (is_bool($accessibleDesignation) && is_bool($accessibleBranch)) {
            return $accessibleDesignation && $accessibleBranch;
        }

        foreach ($employees as $employee) {
            if ($employee->is_default) {
                return false;
            }

            $employeeDesignation = $employee->current_designation_id ?? $employee->lastRecord?->designation_id;
            $employeeBranch = $employee->current_branch_id ?? $employee->lastRecord?->branch_id;

            if (! in_array($employeeDesignation, $accessibleDesignation)) {
                return false;
            }

            if (! in_array($employeeBranch, $accessibleBranch)) {
                return false;
            }
        }

        return true;
    }

    public function validateAccessibleEmployee(Employee $employee, bool $self = true)
    {
        if (! $this->isAccessibleEmployee($employee, $self)) {
            throw ValidationException::withMessages(['message' => trans('employee.permission_denied')]);
        }
    }

    public function validateAccessibleEmployees(Collection $employees)
    {
        if (! $this->areAccessibleEmployees($employees)) {
            throw ValidationException::withMessages(['message' => trans('employee.permission_denied')]);
        }
    }

    public function validateEmployeeJoiningDate(Employee $employee, string $date, string $module = '', string $field = 'message')
    {
        if ($employee->joining_date > $date) {
            throw ValidationException::withMessages([$field => trans('validation.after_or_equal', ['attribute' => $module, 'date' => trans('employee.props.joining_date').' '.CalHelper::showDate($employee->joining_date)])]);
        }
    }

    public function validateEmployeeLeavingDate(Employee $employee, string $date, string $module = '', string $field = 'message')
    {
        if ($employee->leaving_date && $employee->leaving_date < $date) {
            throw ValidationException::withMessages([$field => trans('validation.before_or_equal', ['attribute' => $module, 'date' => trans('employee.props.joining_date').' '.CalHelper::showDate($employee->leaving_date)])]);
        }
    }

    /**
     *  Used to get children from tree structure
     */
    public function getChilds($array, $currentParent = 1, $level = 1, $child = [], $currLevel = 0, $prevLevel = -1): array
    {
        foreach ($array as $categoryId => $category) {
            if ($currentParent === $category) {
                if ($currLevel > $prevLevel) {
                }
                if ($currLevel === $prevLevel) {
                }
                $child[] = $categoryId;
                if ($currLevel > $prevLevel) {
                    $prevLevel = $currLevel;
                }
                $currLevel++;
                if ($level) {
                    $child = $this->getChilds($array, $categoryId, $level, $child, $currLevel, $prevLevel);
                }
                $currLevel--;
            }
        }
        if ($currLevel === $prevLevel) {
        }

        return $child;
    }
}
