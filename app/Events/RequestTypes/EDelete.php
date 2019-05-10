<?php

namespace App\Events\RequestTypes;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_types';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'request_types';
    }
}
