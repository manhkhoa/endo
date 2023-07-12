<?php

namespace App\Http\Resources\Finance;

use App\Helpers\CalHelper;
use App\Helpers\SysHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionRecordResource extends JsonResource
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
            'amount' => SysHelper::formatAmount($this->amount),
            'amount_display' => SysHelper::formatCurrency($this->amount),
            'ledger' => LedgerResource::make($this->whenLoaded('ledger')),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }
}
