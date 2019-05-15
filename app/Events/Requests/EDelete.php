<?php

namespace App\Events\Requests;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'requests';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'requests.' . $this->id;
    }
}
