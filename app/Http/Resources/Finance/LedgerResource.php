<?php

namespace App\Http\Resources\Finance;

use App\Enums\Finance\LedgerGroup;
use App\Helpers\CalHelper;
use App\Helpers\SysHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class LedgerResource extends JsonResource
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
            'description' => $this->description,
            'type' => LedgerTypeResource::make($this->whenLoaded('type')),
            'opening_balance' => SysHelper::formatAmount($this->opening_balance),
            'opening_balance_display' => SysHelper::formatCurrency($this->opening_balance),
            'current_balance' => SysHelper::formatAmount($this->current_balance),
            'current_balance_display' => SysHelper::formatCurrency($this->current_balance),
            'balance' => SysHelper::formatAmount($this->balance),
            'balance_display' => SysHelper::formatCurrency(abs($this->balance)),
            'balance_color' => $this->getBalanceColor(),
            'created_at' => CalHelper::showDateTime($this->created_at),
            'updated_at' => CalHelper::showDateTime($this->updated_at),
        ];
    }

    private function getBalanceColor()
    {
        if ($this->balance == 0) {
            return;
        }

        if ($this->balance > 0 && in_array($this->type->type, [
            LedgerGroup::CASH->value,
            LedgerGroup::BANK_ACCOUNT->value,
            LedgerGroup::DIRECT_INCOME->value,
            LedgerGroup::INDIRECT_INCOME->value,
            LedgerGroup::SUNDRY_DEBTOR->value,
        ])) {
            return 'success';
        }

        if ($this->balance < 0 && in_array($this->type->type, [
            LedgerGroup::OVERDRAFT_ACCOUNT->value,
            LedgerGroup::INDIRECT_EXPENSE->value,
            LedgerGroup::DIRECT_EXPENSE->value,
            LedgerGroup::SUNDRY_CREDITOR->value,
        ])) {
            return 'success';
        }

        return 'danger';
    }
}
