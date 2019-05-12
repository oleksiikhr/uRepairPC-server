<?php

namespace App\Events\RequestPriorities;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_priorities';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'request_priorities';
    }
}
