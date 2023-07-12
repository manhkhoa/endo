<?php

namespace App\Http\Resources\Task;

use App\Helpers\CalHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => CalHelper::toDate($this->due_date),
            'due_date_display' => CalHelper::showDate($this->due_date),
            'due_time' => $this->due_date_time,
            'due' => $this->due,
            'is_overdue' => $this->is_overdue,
            'overdue_days' => $this->overdue_days,
            'overdue_days_display' => trans('task.props.overdue_by', ['day' => $this->overdue_days]),
            'completed_at' => CalHelper::toDateTime($this->completed_at),
            'completed_at_display' => CalHelper::showDateTime($this->completed_at),
            'is_completed' => $this->completed_at ? true : false,
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
