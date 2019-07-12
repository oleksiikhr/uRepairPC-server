<?php

namespace App\Events\RequestTypes;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
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
