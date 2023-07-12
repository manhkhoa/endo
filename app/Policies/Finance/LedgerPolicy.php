<?php

namespace App\Policies\Finance;

use App\Models\Finance\Ledger;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LedgerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can fetch prerequisites any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function preRequisite(User $user)
    {
        return $user->can('ledger:create') || $user->can('ledger:edit');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('ledger:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Ledger  $ledger
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ledger $ledger)
    {
        return $user->can('ledger:read') && $ledger->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('ledger:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Ledger  $ledger
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ledger $ledger)
    {
        return $user->can('ledger:edit') && $ledger->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Ledger  $ledger
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ledger $ledger)
    {
        return $user->can('ledger:delete') && $ledger->team_id == $user->current_team_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Ledger  $ledger
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ledger $ledger)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Ledger  $ledger
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ledger $ledger)
    {
        //
    }
}
