<?php

namespace App\Http\Resources\Employee;

use App\Enums\Gender;
use App\Helpers\CalHelper;
use App\Support\HasPhoto;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeSummaryResource extends JsonResource
{
    use HasPhoto;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'code_number' => $this->code_number,
            'name' => $this->full_name,
            'birth_date' => CalHelper::toDate($this->birth_date),
            'birth_date_display' => CalHelper::showDate($this->birth_date),
            'photo' => $this->getPhoto($this->photo, $this->gender),
            'gender' => $this->gender,
            'gender_display' => Gender::getLabel($this->gender),
            'gender_detail' => Gender::getDetail($this->gender),
            'employment_status' => $this->current_employment_status_name ?? '-',
            'department' => $this->current_department_name ?? '-',
            'designation' => $this->current_designation_name ?? '-',
            'branch' => $this->current_branch_name ?? '-',
            'self' => $this->user_id == auth()->id() ? true : false,
            'joining_date' => CalHelper::toDate($this->joining_date),
            'joining_date_display' => CalHelper::showDate($this->joining_date),
            'leaving_date' => CalHelper::toDate($this->leaving_date),
            'leaving_date_display' => CalHelper::showDate($this->leaving_date),
            'created_at' => CalHelper::showDateTime($this->created_at),
        ];
    }
}
