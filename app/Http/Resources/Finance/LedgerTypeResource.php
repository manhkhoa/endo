<?php

namespace App\Http\Resources\Finance;

use App\Enums\Finance\LedgerGroup;
use App\Helpers\CalHelper;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerTypeResource extends JsonResource
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
            'type' => $this->type,
            'type_display' => LedgerGroup::getLabel($this->type),
            'type_detail' => LedgerGroup::getDetail($this->type),
            'parent' => self::make($this->whenLoaded('parent')),
            'team' => TeamResource::make($this->whenLoaded('team')),
            'has_account' => (bool) $this->getMeta('has_account'),
            'has_contact' => (bool) $this->getMeta('has_contact'),
            'description' => $this->description,
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
