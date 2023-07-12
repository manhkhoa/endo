<?php

namespace App\Models;

use App\Concerns\HasFilter;
use App\Concerns\HasMedia;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Account extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, HasMedia, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'accounts';

    protected $casts = [
        'bank_details' => 'array',
        'meta' => 'array',
    ];

    public function getModelName(): string
    {
        return 'Account';
    }

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('account')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
