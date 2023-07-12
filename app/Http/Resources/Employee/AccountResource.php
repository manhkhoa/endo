<?php

namespace App\Http\Resources\Employee;

use App\Helpers\CalHelper;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AccountResource extends JsonResource
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
            'name' => $this->name,
            'alias' => $this->alias,
            'number' => $this->number,
            'bank_name' => Arr::get($this->bank_details, 'bank_name'),
            'branch_name' => Arr::get($this->bank_details, 'branch_name'),
            'bank_code1' => Arr::get($this->bank_details, 'bank_code1'),
            'bank_code2' => Arr::get($this->bank_details, 'bank_code2'),
            'bank_code2' => Arr::get($this->bank_details, 'bank_code2'),
            'media_token' => $this->getMeta('media_token'),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
