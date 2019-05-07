<?php

namespace App\Events;

class WebsocketUser extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'users';
    }
}
