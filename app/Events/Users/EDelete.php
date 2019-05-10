<?php

namespace App\Events\Users;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
