<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TeamActionService
{
    public function select(Request $request, Team $team): void
    {
        $user = \Auth::user();

        if ($user->current_team_id == $team->id) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_action')]);
        }

        if (! in_array($team->id, config('config.teams', []))) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_input')]);
        }

        $meta = $user->meta;
        $meta['current_team_id'] = $team->id;
        $user->meta = $meta;
        $user->save();

        session()->put(['team_id' => $team->id]);
    }
}
