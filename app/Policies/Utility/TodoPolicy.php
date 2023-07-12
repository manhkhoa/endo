<?php

namespace App\Policies\Utility;

use App\Models\User;
use App\Models\Utility\Todo;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can manage the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Utility\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manage(User $user, Todo $todo)
    {
        return $user->id == $todo->user_id;
    }
}
