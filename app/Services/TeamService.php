<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamService
{
    public function create(Request $request): Team
    {
        \DB::beginTransaction();

        $team = Team::forceCreate($this->formatParams($request));

        \DB::commit();

        return $team;
    }

    private function formatParams(Request $request, ?Team $team = null): array
    {
        $formatted = [
            'name' => $request->name,
        ];

        return $formatted;
    }

    public function update(Request $request, Team $team): void
    {
        \DB::beginTransaction();

        $team->forceFill($this->formatParams($request, $team))->save();

        \DB::commit();
    }

    public function deletable(Team $team): void
    {
        if (Team::count() === 1) {
            throw ValidationException::withMessages(['message' => trans('global.could_not_delete_default', ['attribute' => trans('team.team')])]);
        }

        if (\Auth::user()->current_team_id === $team->id) {
            throw ValidationException::withMessages(['message' => trans('global.could_not_delete_current', ['attribute' => trans('team.team')])]);
        }
    }
}
