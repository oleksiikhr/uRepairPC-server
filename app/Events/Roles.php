<?php

namespace App\Events;

class Roles extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'roles';
    }
}
