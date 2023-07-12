<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\Experience;
use Illuminate\Http\Request;

class ExperienceService
{
    public function preRequisite(Request $request): array
    {
        return [];
    }

    public function findByUuidOrFail(Employee $employee, string $uuid): Experience
    {
        return Experience::whereEmployeeId($employee->id)->whereUuid($uuid)->getOrFail(trans('employee.experience.experience'));
    }

    public function create(Request $request, Employee $employee): Experience
    {
        \DB::beginTransaction();

        $experience = Experience::forceCreate($this->formatParams($request, $employee));

        $experience->addMedia($request);

        \DB::commit();

        return $experience;
    }

    private function formatParams(Request $request, Employee $employee, ?Experience $experience = null): array
    {
        $formatted = [
            'employment_type_id' => $request->employment_type_id,
            'headline' => $request->headline,
            'title' => $request->title,
            'company_name' => $request->company_name,
            'location' => $request->location,
            'job_profile' => $request->job_profile,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        if (! $experience) {
            $formatted['employee_id'] = $employee->id;
        }

        return $formatted;
    }

    public function update(Request $request, Employee $employee, Experience $experience): void
    {
        \DB::beginTransaction();

        $experience->forceFill($this->formatParams($request, $employee, $experience))->save();

        $experience->updateMedia($request);

        \DB::commit();
    }

    public function deletable(Employee $employee, Experience $experience): void
    {
        //
    }
}
