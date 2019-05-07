<?php

namespace App\Events;

class Equipments extends BroadcastWebsocket
{
    /**
     * @return string
     */
    public function section(): string
    {
        return 'equipments';
    }
}
