<?php

namespace App\Events;

class WebsocketRole extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'roles';
    }
}
