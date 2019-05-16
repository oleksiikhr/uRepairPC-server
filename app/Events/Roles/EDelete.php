<?php

namespace App\Events\Roles;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'roles';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'roles.'.$this->id;
    }
}
