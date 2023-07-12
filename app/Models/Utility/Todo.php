<?php

namespace App\Models\Utility;

use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Helpers\CalHelper;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Todo extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'todos';

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'list_id');
    }

    public function scopeFilterByStatus(Builder $query, $status)
    {
        $query->when($status === 'completed', function ($q, $status) {
            $q->whereNotNull('completed_at');
        })->when($status === 'incomplete', function ($q, $status) {
            $q->whereNull('completed_at');
        });
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->completed_at) {
            return false;
        }

        $due = $this->due_date;

        if ($this->due_time) {
            $due = CalHelper::toDateTime($this->due_date.' '.$this->due_time);
        }

        if ($due > today()->toDateTimeString()) {
            return false;
        }

        return true;
    }

    public function getOverdueDaysAttribute(): int
    {
        if (! $this->is_overdue) {
            return 0;
        }

        return CalHelper::dateDiff(today()->toDateString(), $this->due_date);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('todo')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
