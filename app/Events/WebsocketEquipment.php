<?php

namespace App\Events;

class WebsocketEquipment extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'equipments';
    }
}
