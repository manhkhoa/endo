<?php

namespace App\Models\Task;

use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Helpers\CalHelper;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Checklist extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'task_checklists';

    protected $casts = [
        'meta' => 'array',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeFindIfExists(Builder $query, string $uuid, $field = 'message'): self
    {
        return $query->whereUuid($uuid)
            ->getOrFail(trans('task.checklist.checklist'), $field);
    }

    public function getDueDateTimeAttribute()
    {
        if (! $this->due_time) {
            return null;
        }

        return CalHelper::toTime($this->due_date.' '.$this->due_time);
    }

    public function getDueAttribute()
    {
        if (! $this->due_time) {
            return CalHelper::showDate($this->due_date);
        }

        return CalHelper::showDateTime($this->due_date.' '.$this->due_time);
    }

    public function getIsOverdueAttribute(): bool
    {
        if ($this->is_completed) {
            return false;
        }

        if ($this->due_date > today()->toDateString()) {
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
            ->useLogName('task_checklist')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
