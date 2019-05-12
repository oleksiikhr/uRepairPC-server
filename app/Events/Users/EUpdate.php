<?php

namespace App\Events\Users;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'users';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'users.' . $this->id;
    }
}
