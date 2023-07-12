<?php

namespace App\Services\Company;

use App\Models\Company\Designation;
use App\Models\Employee\Record as EmployeeRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesignationService
{
    public function create(Request $request): Designation
    {
        \DB::beginTransaction();

        $designation = Designation::forceCreate($this->formatParams($request));

        \DB::commit();

        return $designation;
    }

    private function formatParams(Request $request, ?Designation $designation = null): array
    {
        $formatted = [
            'name' => $request->name,
            'alias' => $request->alias,
            'parent_id' => $request->designation_id,
            'description' => $request->description,
        ];

        if (! $designation) {
            $formatted['team_id'] = session('team_id');
        }

        return $formatted;
    }

    public function update(Request $request, Designation $designation): void
    {
        $children = $designation->descendents()->pluck('uuid')->all();

        if (in_array($request->parent, $children)) {
            throw ValidationException::withMessages(['message' => trans('global.child_cannot_become_parent', ['attribute' => trans('company.designation.designation')])]);
        }

        \DB::beginTransaction();

        $designation->forceFill($this->formatParams($request, $designation))->save();

        \DB::commit();
    }

    public function deletable(Designation $designation): void
    {
        $parentExists = Designation::whereParentId($designation->id)->exists();

        if ($parentExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_parent_dependency', ['attribute' => trans('company.designation.designation')])]);
        }

        $employeeRecordExists = EmployeeRecord::whereDesignationId($designation->id)->exists();

        if ($employeeRecordExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('company.designation.designation'), 'dependency' => trans('employee.employee')])]);
        }
    }
}
