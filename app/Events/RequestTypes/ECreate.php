<?php

namespace App\Events\RequestTypes;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
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
