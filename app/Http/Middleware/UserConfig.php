<?php

namespace App\Http\Middleware;

use App\Helpers\SysHelper;
use Closure;
use Illuminate\Http\Request;

class UserConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty(auth()->check())) {
            return $next($request);
        }

        config([
            'config.display_timezone' => \Auth::user()->timezone ?? config('config.system.timezone'),
            'config.system.locale' => \Auth::user()->getPreference('system.locale') ?? config('config.system.locale'),
        ]);

        $allowedTeamIds = \Auth::user()->getAllowedTeamIds();

        config([
            'config.teams_set' => true,
            'config.teams' => $allowedTeamIds,
        ]);

        if (in_array(session('team_id'), $allowedTeamIds)) {
            SysHelper::setTeam(session('team_id'));
        } elseif (! $request->route()->named('teams.select')) {
            \Auth::guard('web')->logout();

            return response()->json(['message' => __('team.could_not_find_selected_team')], 422);
        }

        return $next($request);
    }
}
