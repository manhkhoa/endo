<?php

namespace App\Http\Resources\Employee;

use App\Helpers\CalHelper;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
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
            'full_name' => $this->contact->name,
            'is_default' => $this->is_default,
            'self' => $this->user_id == auth()->id() ? true : false,
            'contact' => ContactResource::make($this->whenLoaded('contact')),
            // 'last_record'          => RecordResource::make($this->whenLoaded('lastRecord')),
            'last_record' => [
                'period' => CalHelper::getPeriod($this->start_date, $this->end_date),
                'duration' => CalHelper::getDuration($this->start_date, $this->end_date),
                'department' => ['name' => $this->department_name, 'uuid' => $this->department_uuid],
                'designation' => ['name' => $this->designation_name, 'uuid' => $this->designation_uuid],
                'branch' => ['name' => $this->branch_name, 'uuid' => $this->branch_uuid],
                'employment_status' => ['name' => $this->employment_status_name, 'uuid' => $this->employment_status_uuid],
            ],
            'start_date' => CalHelper::toDate($this->start_date),
            'start_date_display' => CalHelper::showDate($this->start_date),
            'end_date' => CalHelper::toDate($this->end_date),
            'end_date_display' => CalHelper::showDate($this->end_date),
            'joining_date' => CalHelper::toDate($this->joining_date),
            'joining_date_display' => CalHelper::showDate($this->joining_date),
            'leaving_date' => CalHelper::toDate($this->leaving_date),
            'leaving_date_display' => CalHelper::showDate($this->leaving_date),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
