<?php

namespace App\Http\Resources;

use App\Enums\Gender;
use App\Helpers\CalHelper;
use App\Support\HasPhoto;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ContactResource extends JsonResource
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
            'name' => $this->name,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'third_name' => $this->third_name,
            'last_name' => $this->last_name,
            'birth_date' => CalHelper::toDate($this->birth_date),
            'birth_date_display' => CalHelper::showDate($this->birth_date),
            'anniversary_date' => CalHelper::toDate($this->anniversary_date),
            'anniversary_date_display' => CalHelper::showDate($this->anniversary_date),
            'user' => UserSummaryResource::make($this->whenLoaded('user')),
            'contact_number' => $this->contact_number,
            'email' => $this->email,
            'nationality' => $this->nationality,
            'birth_place' => $this->birth_place,
            'mother_tongue' => $this->mother_tongue,
            'photo' => $this->getPhoto($this->photo, $this->gender),
            'unique_id_number1' => $this->unique_id_number1,
            'unique_id_number2' => $this->unique_id_number2,
            'unique_id_number3' => $this->unique_id_number3,
            'alternate_records' => [
                'contact_number' => Arr::get($this->alternate_records, 'contact_number'),
                'email' => Arr::get($this->alternate_records, 'email'),
            ],
            'present_address' => $this->present_address,
            'present_address_display' => Arr::implode(Arr::notEmpty($this->present_address)),
            'same_as_present_address' => $this->same_as_present_address,
            'permanent_address' => $this->permanent_address,
            'permanent_address_display' => Arr::implode(Arr::notEmpty($this->permanent_address)),
            'gender' => $this->gender,
            'gender_display' => Gender::getLabel($this->gender),
            'gender_detail' => Gender::getDetail($this->gender),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
