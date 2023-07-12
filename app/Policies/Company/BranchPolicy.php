<?php

namespace App\Policies\Company;

use App\Concerns\SubordinateAccess;
use App\Models\Company\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization, SubordinateAccess;

    private function isAccessible(Branch $branch)
    {
        $accessibleBranchIds = $this->getAccessibleBranch();

        if (is_bool($accessibleBranchIds)) {
            return $accessibleBranchIds;
        }

        if (in_array($branch->id, $accessibleBranchIds)) {
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
        return $user->can('branch:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Branch  $branch
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Branch $branch)
    {
        if (! $user->can('branch:read')) {
            return false;
        }

        if ($branch->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($branch);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('branch:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Branch  $branch
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Branch $branch)
    {
        if (! $user->can('branch:edit')) {
            return false;
        }

        if ($branch->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($branch);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Branch  $branch
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Branch $branch)
    {
        if (! $user->can('branch:delete')) {
            return false;
        }

        if ($branch->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($branch);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Branch  $branch
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Branch $branch)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company\Branch  $branch
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Branch $branch)
    {
        //
    }
}
