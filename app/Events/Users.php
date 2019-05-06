<?php

namespace App\Events;

class Users extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'users';
    }
}
