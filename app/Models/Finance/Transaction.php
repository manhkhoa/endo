<?php

namespace App\Models\Finance;

use App\Concerns\HasFilter;
use App\Concerns\HasMedia;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, HasMedia, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'transactions';

    protected $casts = [
        'meta' => 'array',
    ];

    public function getModelName(): string
    {
        return 'Transaction';
    }

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(TransactionRecord::class, 'transaction_id');
    }

    public function record(): BelongsTo
    {
        return $this->belongsTo(TransactionRecord::class);
    }

    public function scopeWithRecord(Builder $query)
    {
        $query->addSelect(['record_id' => TransactionRecord::select('id')
            ->whereColumn('transaction_id', 'transactions.id')
            ->limit(1),
        ])->with('record', 'record.ledger');
    }

    public function scopeByTeam(Builder $query)
    {
        $query->whereHas('ledger', function ($q) {
            $q->whereTeamId(session('team_id'));
        });
    }

    public function scopeFindIfExists(Builder $query, string $uuid, $field = 'message')
    {
        $transaction = $query->whereUuid($uuid)
            ->with('ledger')
            ->withRecord()
            ->getOrFail(trans('finance.transaction.transaction'), $field);

        $transaction->employee = Employee::withUserRecordId($transaction->user_id);

        return $transaction;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('transaction')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
