<?php

namespace App\Events\RequestPriorities;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
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
