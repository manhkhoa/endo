<?php

namespace App\Policies\Company;

use App\Concerns\SubordinateAccess;
use App\Models\Company\Designation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DesignationPolicy
{
    use HandlesAuthorization, SubordinateAccess;

    private function isAccessible(Designation $designation)
    {
        $accessibleDesignationIds = $this->getAccessibleDesignation();

        if (is_bool($accessibleDesignationIds)) {
            return $accessibleDesignationIds;
        }

        if (in_array($designation->id, $accessibleDesignationIds)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('designation:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Designation  $designation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Designation $designation)
    {
        if (! $user->can('designation:read')) {
            return false;
        }

        if ($designation->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($designation);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('designation:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Designation  $designation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Designation $designation)
    {
        if (! $user->can('designation:edit')) {
            return false;
        }

        if ($designation->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($designation);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Designation  $designation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Designation $designation)
    {
        if (! $user->can('designation:delete')) {
            return false;
        }

        if ($designation->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($designation);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Designation  $designation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Designation $designation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Designation  $designation
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Designation $designation)
    {
        //
    }
}
