<?php

namespace App\Listeners;

use App\Events\Auth\UserLogin;
use Mint\Service\Actions\CheckForUpdate;

class UserLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Auth\UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        (new CheckForUpdate)->execute();
    }
}
