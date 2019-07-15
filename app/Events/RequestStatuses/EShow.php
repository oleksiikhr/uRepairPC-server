<?php

namespace App\Events\RequestStatuses;

use App\Events\Common\EJoinBroadcast;

class EShow extends EJoinBroadcast
{
    use EModel;

    public function __construct()
    {
        parent::__construct($this->roomName, false);
    }
}