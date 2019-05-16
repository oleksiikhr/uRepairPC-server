<?php

namespace App\Events\RequestStatuses;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_statuses';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'request_statuses';
    }
}
