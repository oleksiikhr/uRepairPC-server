<?php

namespace App\Events\RequestPriorities;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
