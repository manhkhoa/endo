<?php

namespace App\Http\Resources\Employee;

use App\Helpers\CalHelper;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
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
            'department' => ['name' => $this->department_name, 'uuid' => $this->department_uuid],
            'designation' => ['name' => $this->designation_name, 'uuid' => $this->designation_uuid],
            'branch' => ['name' => $this->branch_name, 'uuid' => $this->branch_uuid],
            'employment_status' => ['name' => $this->employment_status_name, 'uuid' => $this->employment_status_uuid],
            'start_date' => CalHelper::toDate($this->start_date),
            'start_date_display' => CalHelper::showDate($this->start_date),
            'end_date' => CalHelper::toDate($this->end_date),
            'end_date_display' => CalHelper::showDate($this->end_date),
            'period' => CalHelper::getPeriod($this->start_date, $this->end_date),
            'duration' => CalHelper::getDuration($this->start_date, $this->end_date),
            'remarks' => $this->remarks,
            'media_token' => $this->getMeta('media_token'),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'is_ended' => (bool) $this->getMeta('is_ended'),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
