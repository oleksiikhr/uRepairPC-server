<?php

namespace App\Events\RequestStatuses;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return $this->roomName;
    }
}
