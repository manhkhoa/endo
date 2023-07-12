<?php

namespace App\Policies\Calendar;

use App\Models\Calendar\Holiday;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('holiday:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Holiday $holiday)
    {
        return $user->can('holiday:read') && $holiday->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('holiday:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Holiday $holiday)
    {
        return $user->can('holiday:edit') && $holiday->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Holiday $holiday)
    {
        return $user->can('holiday:delete') && $holiday->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Holiday $holiday)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Calendar\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Holiday $holiday)
    {
        //
    }
}
