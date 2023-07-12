<?php

namespace App\Services\Company;

use App\Models\Company\Branch;
use App\Models\Employee\Record as EmployeeRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BranchService
{
    public function create(Request $request): Branch
    {
        \DB::beginTransaction();

        $branch = Branch::forceCreate($this->formatParams($request));

        \DB::commit();

        return $branch;
    }

    private function formatParams(Request $request, ?Branch $branch = null): array
    {
        $formatted = [
            'name' => $request->name,
            'code' => $request->code,
            'alias' => $request->alias,
            'parent_id' => $request->branch_id,
            'description' => $request->description,
        ];

        if (! $branch) {
            $formatted['team_id'] = session('team_id');
        }

        return $formatted;
    }

    public function update(Request $request, Branch $branch): void
    {
        $children = $branch->descendents()->pluck('uuid')->all();

        if (in_array($request->parent, $children)) {
            throw ValidationException::withMessages(['message' => trans('global.child_cannot_become_parent', ['attribute' => trans('company.branch.branch')])]);
        }

        \DB::beginTransaction();

        $branch->forceFill($this->formatParams($request, $branch))->save();

        \DB::commit();
    }

    public function deletable(Branch $branch): void
    {
        $parentExists = Branch::whereParentId($branch->id)->exists();

        if ($parentExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_parent_dependency', ['attribute' => trans('company.branch.branch')])]);
        }

        $employeeRecordExists = EmployeeRecord::whereBranchId($branch->id)->exists();

        if ($employeeRecordExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('company.branch.branch'), 'dependency' => trans('employee.employee')])]);
        }
    }
}
