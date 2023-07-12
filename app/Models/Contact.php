<?php

namespace App\Models;

use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Contact extends Model
{
    use HasFactory, HasFilter, HasUuid, HasMeta, LogsActivity;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'contacts';

    protected $casts = [
        'alternate_records' => 'array',
        'address' => 'array',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function scopeByTeam(Builder $query)
    {
        $query->whereTeamId(session('team_id'));
    }

    public function getNameAttribute()
    {
        return ucwords(preg_replace('/\s+/', ' ', $this->first_name.' '.$this->middle_name.' '.$this->third_name.' '.$this->last_name));
    }

    public function getNameWithNumberAttribute()
    {
        return ucwords(preg_replace('/\s+/', ' ', $this->first_name.' '.$this->middle_name.' '.$this->third_name.' '.$this->last_name)).' '.$this->contact_number;
    }

    public function getPresentAddressAttribute()
    {
        return [
            'address_line1' => Arr::get($this->address, 'present.address_line1'),
            'address_line2' => Arr::get($this->address, 'present.address_line2'),
            'city' => Arr::get($this->address, 'present.city'),
            'state' => Arr::get($this->address, 'present.state'),
            'zipcode' => Arr::get($this->address, 'present.zipcode'),
            'country' => Arr::get($this->address, 'present.country'),
        ];
    }

    public function getSameAsPresentAddressAttribute()
    {
        return (bool) Arr::get($this->address, 'permanent.same_as_present_address');
    }

    public function getPermanentAddressAttribute()
    {
        return [
            'same_as_present_address' => $this->same_as_present_address,
            'address_line1' => Arr::get($this->address, 'permanent.address_line1'),
            'address_line2' => Arr::get($this->address, 'permanent.address_line2'),
            'city' => Arr::get($this->address, 'permanent.city'),
            'state' => Arr::get($this->address, 'permanent.state'),
            'zipcode' => Arr::get($this->address, 'permanent.zipcode'),
            'country' => Arr::get($this->address, 'permanent.country'),
        ];
    }

    public function scopeSearchByName(Builder $query, $name = null)
    {
        collect(explode(' ', $name))->filter()->each(function ($name) use ($query) {
            $query->where(function ($query) use ($name) {
                collect(['first_name', 'middle_name', 'third_name', 'last_name'])->each(function ($column) use ($query, $name) {
                    $query->orWhere($column, 'like', "%{$name}%");
                });
            });
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('contact')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
