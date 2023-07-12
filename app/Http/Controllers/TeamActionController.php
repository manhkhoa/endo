<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\TeamActionService;
use Illuminate\Http\Request;

class TeamActionController extends Controller
{
    public function select(Request $request, Team $team, TeamActionService $service)
    {
        $service->select($request, $team);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('team.current_team')]),
        ]);
    }
}
