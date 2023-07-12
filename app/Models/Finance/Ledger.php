<?php

namespace App\Models\Finance;

use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Enums\Finance\LedgerGroup;
use App\Enums\Finance\TransactionType;
use App\Helpers\SysHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ledger extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'ledgers';

    protected $casts = [
        'meta' => 'array',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LedgerType::class, 'ledger_type_id');
    }

    public function getBalanceAttribute(): float
    {
        return SysHelper::formatAmount($this->opening_balance + $this->current_balance);
    }

    public function scopeByTeam(Builder $query)
    {
        $query->whereTeamId(session('team_id'));
    }

    public function scopeSubType(Builder $query, ?string $subType = null)
    {
        $query->when($subType, function ($q, $subType) {
            $q->whereHas('type', function ($q) use ($subType) {
                if ($subType == 'primary') {
                    $q->whereIn('type', LedgerGroup::primaryLedgers());
                } elseif ($subType == 'secondary') {
                    $q->whereIn('type', LedgerGroup::secondaryLedgers());
                }
            });
        });
    }

    public function updatePrimaryBalance(string $transactionType, float $amount = 0)
    {
        $this->increment('current_balance', $this->primaryMultiplier($transactionType) * $amount);
    }

    public function reversePrimaryBalance(string $transactionType, float $amount = 0)
    {
        $this->decrement('current_balance', $this->primaryMultiplier($transactionType) * $amount);
    }

    public function reverseSecondaryBalance(string $transactionType, float $amount = 0)
    {
        $this->increment('current_balance', $this->secondaryMultiplier($transactionType) * $amount);
    }

    public function updateSecondaryBalance(string $transactionType, float $amount = 0)
    {
        $this->decrement('current_balance', $this->secondaryMultiplier($transactionType) * $amount);
    }

    public function primaryMultiplier(string $transactionType): int
    {
        if ($transactionType == TransactionType::PAYMENT->value && in_array($this->type->type, [LedgerGroup::CASH->value, LedgerGroup::BANK_ACCOUNT->value])) {
            return -1;
        }

        if ($transactionType == TransactionType::RECEIPT->value && in_array($this->type->type, [LedgerGroup::OVERDRAFT_ACCOUNT->value])) {
            return -1;
        }

        if ($transactionType == TransactionType::CONTRA->value && in_array($this->type->type, [LedgerGroup::OVERDRAFT_ACCOUNT->value])) {
            return -1;
        }

        return 1;
    }

    public function secondaryMultiplier(string $transactionType): int
    {
        if ($transactionType == TransactionType::PAYMENT->value && in_array($this->type->type, [LedgerGroup::SUNDRY_DEBTOR->value, LedgerGroup::DIRECT_INCOME->value, LedgerGroup::INDIRECT_INCOME->value])) {
            return -1;
        }

        if ($transactionType == TransactionType::RECEIPT->value && in_array($this->type->type, [LedgerGroup::SUNDRY_CREDITOR->value, LedgerGroup::DIRECT_EXPENSE->value, LedgerGroup::INDIRECT_EXPENSE->value])) {
            return -1;
        }

        if ($transactionType == TransactionType::CONTRA->value && in_array($this->type->type, [LedgerGroup::OVERDRAFT_ACCOUNT->value])) {
            return -1;
        }

        return 1;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ledger')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
