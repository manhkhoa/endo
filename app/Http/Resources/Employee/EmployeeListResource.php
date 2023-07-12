<?php

namespace App\Http\Resources\Employee;

use App\Enums\Employee\Status;
use App\Enums\Gender;
use App\Helpers\CalHelper;
use App\Support\HasPhoto;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListResource extends JsonResource
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
        $status = Status::ACTIVE->value;

        if ($this->leaving_date && $this->leaving_date < today()->toDateString()) {
            $status = Status::INACTIVE->value;
        }

        return [
            'uuid' => $this->uuid,
            'code_number' => $this->code_number,
            'name' => $this->full_name,
            'employment_status' => $this->employment_status_name ?? '-',
            'department' => $this->department_name ?? '-',
            'designation' => $this->designation_name ?? '-',
            'branch' => $this->branch_name ?? '-',
            'employment_status_uuid' => $this->employment_status_uuid,
            'department_uuid' => $this->department_uuid,
            'designation_uuid' => $this->designation_uuid,
            'branch_uuid' => $this->branch_uuid,
            'is_default' => $this->is_default,
            'gender' => $this->gender,
            'gender_display' => Gender::getLabel($this->gender),
            'gender_detail' => Gender::getDetail($this->gender),
            'photo' => $this->getPhoto($this->photo, $this->gender),
            'self' => $this->user_id == auth()->id() ? true : false,
            'birth_date' => CalHelper::toDate($this->birth_date),
            'birth_date_display' => CalHelper::showDate($this->birth_date),
            'age' => CalHelper::getAge($this->birth_date),
            'age_display' => CalHelper::getAgeDisplay($this->birth_date),
            'age_short_display' => CalHelper::getAgeShortDisplay($this->birth_date),
            'joining_date' => CalHelper::toDate($this->joining_date),
            'joining_date_display' => CalHelper::showDate($this->joining_date),
            'leaving_date' => CalHelper::toDate($this->leaving_date),
            'leaving_date_display' => CalHelper::showDate($this->leaving_date),
            'period' => CalHelper::getPeriod($this->joining_date, $this->leaving_date),
            'status' => $status,
            'status_display' => Status::getLabel($status),
            'status_detail' => Status::getDetail($status),
            'start_date' => CalHelper::toDate($this->start_date),
            'start_date_display' => CalHelper::showDate($this->start_date),
            'end_date' => CalHelper::toDate($this->end_date),
            'end_date_display' => CalHelper::showDate($this->end_date),
            'created_at' => CalHelper::showDateTime($this->created_at),
        ];
    }
}
