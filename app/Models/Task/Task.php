<?php

namespace App\Models\Task;

use App\Concerns\HasFilter;
use App\Concerns\HasMedia;
use App\Concerns\HasMeta;
use App\Concerns\HasTags;
use App\Concerns\HasUuid;
use App\Concerns\Task\TaskAction;
use App\Concerns\Task\TaskConstraint;
use App\Helpers\CalHelper;
use App\Models\Employee\Employee;
use App\Models\Option;
use App\Models\Tag;
use App\Scopes\Task\TaskScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, HasTags, HasMedia, TaskScope, TaskAction, TaskConstraint, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'tasks';

    protected $casts = [
        'meta' => 'array',
        'config' => 'array',
        'repeatation' => 'array',
    ];

    public function getModelName(): string
    {
        return 'Task';
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function memberLists(): HasMany
    {
        return $this->hasMany(Member::class, 'task_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'task_priority_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'task_category_id');
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'task_list_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'owner_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'member_employee_id');
    }

    public function getIsOwnerAttribute(): bool
    {
        return $this->owner?->user_id == auth()->id() ? true : false;
    }

    public function getIsMemberAttribute(): bool
    {
        return $this->member_user_id == auth()->id() ? true : false;
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->completed_at ? true : false;
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
            ->useLogName('task')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
