<?php

namespace App\Http\Resources\Utility;

use App\Helpers\CalHelper;
use App\Http\Resources\OptionResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'due_date' => CalHelper::toDate($this->due_date),
            'due_time' => $this->getDueTime(),
            'due' => $this->getDue($request),
            'is_due_today' => $this->due_date == today()->toDateString() ? true : false,
            'is_overdue' => $this->is_overdue,
            $this->mergeWhen($this->is_overdue, [
                'overdue_days' => $this->overdue_days,
                'overdue_days_display' => trans('utility.todo.props.overdue_by', ['day' => $this->overdue_days]),
            ]),
            'completed_at' => $this->when($this->completed_at, CalHelper::showDateTime($this->completed_at)),
            'is_archived' => $this->archived_at ? true : false,
            'archived_at' => CalHelper::showDateTime($this->archived_at),
            'description' => $this->description,
            'status' => $this->getStatus($request),
            'list' => OptionResource::make($this->whenLoaded('list')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }

    private function getDueTime()
    {
        if (! $this->due_time) {
            return null;
        }

        return CalHelper::toTime($this->due_date.' '.$this->due_time);
    }

    private function getDue($request)
    {
        if (! $this->due_time) {
            return CalHelper::showDate($this->due_date);
        }

        return CalHelper::showDateTime($this->due_date.' '.$this->due_time);
    }

    private function getStatus($request)
    {
        if ($request->export) {
            return $this->completed_at ? trans('utility.todo.completed') : trans('utility.todo.incomplete');
        }

        return $this->completed_at ? true : false;
    }
}
