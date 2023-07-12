<?php

namespace App\Policies\Finance;

use App\Concerns\SubordinateAccess;
use App\Models\Finance\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, SubordinateAccess;

    /**
     * Determine whether the user can fetch prerequisites any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function preRequisite(User $user)
    {
        return $user->can('transaction:create') || $user->can('transaction:edit');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('transaction:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, transaction $transaction)
    {
        if (! $user->can('transaction:read')) {
            return false;
        }

        if ($transaction?->ledger?->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessibleEmployee($transaction->employee);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('transaction:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, transaction $transaction)
    {
        if (! $user->can('transaction:edit')) {
            return false;
        }

        if ($transaction?->ledger?->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessibleEmployee($transaction->employee);
    }

    /**
     * Determine whether the user can cancel the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function cancel(User $user, transaction $transaction)
    {
        if (! $user->can('transaction:cancel')) {
            return false;
        }

        if ($transaction?->ledger?->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessibleEmployee($transaction->employee);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, transaction $transaction)
    {
        if (! $user->can('transaction:delete')) {
            return false;
        }

        if ($transaction?->ledger?->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessibleEmployee($transaction->employee);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, transaction $transaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Finance\Transaction  $transaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, transaction $transaction)
    {
        //
    }
}
