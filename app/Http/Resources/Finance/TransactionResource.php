<?php

namespace App\Http\Resources\Finance;

use App\Enums\Finance\TransactionType;
use App\Helpers\CalHelper;
use App\Helpers\SysHelper;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'type' => $this->type,
            'type_display' => TransactionType::getLabel($this->type),
            'type_detail' => TransactionType::getDetail($this->type),
            'ledger' => LedgerResource::make($this->whenLoaded('ledger')),
            'record' => TransactionRecordResource::make($this->whenLoaded('record')),
            'date' => CalHelper::toDate($this->date),
            'date_display' => CalHelper::showDate($this->date),
            'amount' => SysHelper::formatAmount($this->amount),
            'amount_display' => SysHelper::formatCurrency($this->amount),
            'description' => $this->description,
            'media_token' => $this->getMeta('media_token'),
            'is_cancelled' => $this->cancelled_at ? true : false,
            'cancelled_at' => CalHelper::showDateTime($this->cancelled_at),
            'media' => MediaResource::collection($this->whenLoaded('media')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
