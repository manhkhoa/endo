<?php

namespace App\Http\Resources\Team;

use App\Helpers\CalHelper;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permission = Arr::getVar('permission');
        $roles = Arr::get($permission, 'roles', []);

        return [
            'uuid' => $this->uuid,
            'label' => Str::toWord($this->name),
            'name' => $this->name,
            'is_default' => array_search(strtolower($this->name), $roles) === false ? false : true,
            // 'team'       => TeamResource::make($this->whenLoaded('team')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
