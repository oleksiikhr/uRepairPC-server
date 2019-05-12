<?php

namespace App\Events\RequestStatuses;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
