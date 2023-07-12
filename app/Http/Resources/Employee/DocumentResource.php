<?php

namespace App\Http\Resources\Employee;

use App\Helpers\CalHelper;
use App\Http\Resources\MediaResource;
use App\Http\Resources\OptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'type' => OptionResource::make($this->whenLoaded('type')),
            'start_date' => CalHelper::toDate($this->start_date),
            'start_date_display' => CalHelper::showDate($this->start_date),
            'end_date' => CalHelper::toDate($this->end_date),
            'end_date_display' => CalHelper::showDate($this->end_date),
            'media_token' => $this->getMeta('media_token'),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
