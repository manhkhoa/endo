<?php

namespace App\Policies\Employee;

use App\Concerns\SubordinateAccess;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization, SubordinateAccess;

    private function isAccessible(Employee $employee)
    {
        return $this->isAccessibleEmployee($employee);
    }

    /**
     * Determine whether the user can fetch prerequisites any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function preRequisite(User $user)
    {
        return $user->can('employee:create') || $user->can('employee:edit');
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('employee:read');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Employee $employee)
    {
        if (! $user->can('employee:read')) {
            return false;
        }

        if ($employee->team_id != $user->current_team_id) {
            return false;
        }

        if ($employee?->user_id == auth()->id()) {
            return true;
        }

        return $this->isAccessible($employee);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('employee:create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Employee $employee)
    {
        if (! $user->can('employee:edit')) {
            return false;
        }

        if ($employee->team_id != $user->current_team_id) {
            return false;
        }

        if ($employee->user_id == auth()->id()) {
            return false;
        }

        return $this->isAccessible($employee);
    }

    /**
     * Determine whether the user can fetch employment record
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function fetchEmploymentRecord(User $user, Employee $employee)
    {
        if ($employee->user_id == $user->id) {
            return true;
        }

        if (! $user->can('employment-record:manage')) {
            return false;
        }

        if ($employee->team_id != $user->current_team_id) {
            return false;
        }

        return $this->isAccessible($employee);
    }

    /**
     * Determine whether the user can manage employment record
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageEmploymentRecord(User $user, Employee $employee)
    {
        if (! $user->can('employment-record:manage')) {
            return false;
        }

        if ($employee->team_id != $user->current_team_id) {
            return false;
        }

        if ($employee->is_default) {
            return false;
        }

        if ($employee->user_id == auth()->id()) {
            return false;
        }

        return $this->isAccessible($employee);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Employee $employee)
    {
        if (! $user->can('employee:delete')) {
            return false;
        }

        if ($employee->team_id != $user->current_team_id) {
            return false;
        }

        if ($employee->is_default) {
            return false;
        }

        if ($employee->user_id == auth()->id()) {
            return false;
        }

        return $this->isAccessible($employee);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Employee $employee)
    {
        //
    }
}
