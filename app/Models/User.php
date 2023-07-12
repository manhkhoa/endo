<?php

namespace App\Models;

use App\Concerns\Auth\TwoFactorSecurity;
use App\Concerns\HasFilter;
use App\Concerns\HasMeta;
use App\Concerns\HasUuid;
use App\Enums\UserStatus;
use App\Events\Auth\UserLogin;
use App\Helpers\SysHelper;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasFilter, HasMeta, HasUuid, HasApiTokens, TwoFactorSecurity, HasRoles, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pending_update',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'pending_update' => 'array',
        'preference' => 'array',
    ];

    public function getIsDefaultAttribute()
    {
        return $this->getMeta('is_default') ? true : false;
    }

    public function getTimezoneAttribute()
    {
        return Arr::get($this->preference, 'system.timezone', config('config.system.timezone', config('app.timezone')));
    }

    public function getUserSidebarAttribute()
    {
        return Arr::get($this->preference, 'layout.sidebar', config('config.system.enable_mini_sidebar') ? 'mini' : 'pinned');
    }

    public function getUserDisplayAttribute()
    {
        return Arr::get($this->preference, 'layout.display', config('config.system.enable_dark_theme') ? 'dark' : 'light');
    }

    public function getUserPreferenceAttribute()
    {
        return [
            'system' => [
                'locale' => Arr::get($this->preference, 'system.locale', config('config.system.locale')),
                'timezone' => $this->timezone,
                'date_format' => Arr::get($this->preference, 'system.date_format', config('config.system.date_format')),
                'time_format' => Arr::get($this->preference, 'system.time_format', config('config.system.time_format')),
            ],
            'layout' => [
                'sidebar' => $this->user_sidebar,
                'display' => $this->user_display,
            ],
        ];
    }

    public function getUserRoleAttribute()
    {
        if (empty(\Auth::user())) {
            return [];
        }

        if (\Auth::user()->hasRole('admin')) {
            return ['*'];
        }

        return $this->roles()->pluck('name')->all();
    }

    public function getUserPermissionAttribute()
    {
        if (empty(\Auth::user())) {
            return [];
        }

        if (\Auth::user()->hasRole('admin')) {
            return ['*'];
        }

        return $this->getAllPermissions()->pluck('name')->all();
    }

    public function getAvatarAttribute(): string
    {
        $default = '/images/male.png';
        $avatar = $this->getMeta('avatar');

        if (! $avatar) {
            return $default;
        }

        if (! \Storage::disk('public')->exists(str_replace('/storage/', '', $avatar))) {
            return $default;
        }

        return $avatar;
    }

    public function getPendingUpdate($option)
    {
        return Arr::get($this->pending_update, $option);
    }

    public function getAllowedTeamIds(): array
    {
        return \DB::table('model_has_roles')->whereModelId($this->id)->whereModelType('User')->get()->pluck('team_id')->all();
    }

    public function validateCurrentTeamId()
    {
        $allowedTeamIds = $this->getAllowedTeamIds();

        $currentTeamId = $this->getMeta('current_team_id');
        $meta = $this->meta;

        if (
            ($currentTeamId && ! in_array($currentTeamId, $allowedTeamIds)) ||
            ! $currentTeamId
        ) {
            $meta['current_team_id'] = Arr::first($allowedTeamIds);
            $this->meta = $meta;
            $this->save();
        }
    }

    public function setCurrentTeamId()
    {
        $currentTeamId = $this->getMeta('current_team_id');
        if (! $currentTeamId) {
            return;
        }

        session(['team_id' => $currentTeamId]);
        SysHelper::setTeam($currentTeamId);
    }

    public function getCurrentTeamIdAttribute()
    {
        $currentTeamId = $this->getMeta('current_team_id');

        if (! config('config.teams_set', false)) {
            return $currentTeamId;
        }

        $allowedTeamIds = config('config.teams', []);

        if ($this->is_default && $currentTeamId) {
            return $currentTeamId;
        }

        if (in_array($currentTeamId, $allowedTeamIds)) {
            return $currentTeamId;
        }

        return Arr::first($allowedTeamIds);
    }

    // Constrains

    public function validateStatus($auth_event = true): void
    {
        if (! $this->is_default && ! $this->can('login:action')) {
            $this->logout();
            throw ValidationException::withMessages(['message' => __('auth.login.errors.permission_disabled')]);
        }

        if ($this->status != UserStatus::ACTIVATED->value) {
            $this->logout();
            throw ValidationException::withMessages(['message' => __('auth.login.errors.invalid_status.'.$this->status)]);
        }

        if (config('config.system.enable_maintenance_mode') && ! $this->is_default) {
            $this->logout();
            throw ValidationException::withMessages(['message' => config('config.system.maintenance_mode_message', trans('general.errors.under_maintenance'))]);
        }

        $employee = Employee::auth()->first();

        if (! $employee) {
            $this->logout();
            throw ValidationException::withMessages(['message' => __('employee.no_employee_associated')]);
        }

        if ($auth_event) {
            event(new UserLogin($this));
        }
    }

    public function logout(): void
    {
        activity('user')->log('logged_out');

        \Auth::guard('web')->logout();
    }

    public function getPreference(string $option)
    {
        return Arr::get($this->preference, $option);
    }

    public function isEditable()
    {
        if ($this->getMeta('is_default')) {
            return false;
        }

        if (! \Auth::user()->is_default && $this->hasRole('admin')) {
            return false;
        }

        if ($this->id == \Auth::id()) {
            return false;
        }

        return true;
    }

    public function scopeIsNotAdmin(Builder $query): void
    {
        $query->where(function ($q) {
            $q->where('meta->is_default', null)->orWhere('meta->is_default', false);
        });
    }

    public function isTeamMember(): bool
    {
        if ($this->is_default) {
            return false;
        }

        if (\Auth::user()->is_default) {
            return true;
        }

        if ($this->roles()->firstWhere('model_has_roles.team_id', 1)) {
            return true;
        }

        return false;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logAll()
            ->logExcept(['updated_at'])
            ->logOnlyDirty();
    }
}
