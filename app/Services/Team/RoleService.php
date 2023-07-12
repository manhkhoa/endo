<?php

namespace App\Services\Team;

use App\Models\Team;
use App\Models\Team\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleService
{
    public function create(Request $request, Team $team): Role
    {
        \DB::beginTransaction();

        $role = Role::forceCreate($this->formatParams($request, $team));

        \DB::commit();

        return $role;
    }

    private function formatParams(Request $request, Team $team, ?Role $role = null): array
    {
        $formatted = [
            'name' => Str::kebab($request->name),
            'guard_name' => 'web',
            'team_id' => $team->id,
        ];

        return $formatted;
    }

    public function deletable(Team $team, Role $role): void
    {
    }
}
