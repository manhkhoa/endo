<?php

namespace App\Models\Team;

use App\Concerns\HasFilter;
use App\Concerns\HasUuid;
use App\Http\Resources\Team\RoleResource;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    use HasFilter, HasUuid;

    protected $fillable = [];

    protected $primaryKey = 'id';

    protected $table = 'roles';

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public static function selectOption()
    {
        return RoleResource::collection(self::with('team')->when(! \Auth::user()->is_default, function ($q) {
            $q->whereNotIn('name', ['admin']);
        })->where(function ($q) {
            $q->whereTeamId(session('team_id'))->orWhereNull('team_id');
        })->get());
    }
}
