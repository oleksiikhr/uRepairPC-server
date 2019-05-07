<?php

namespace App\Interfaces;

interface IBroadcastWebsocket
{
    /**
     * Uses in emit name, title and broadcastOn.
     *
     * @return string
     */
    public function section(): string;
}
