<?php

namespace App\Events\Roles;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
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
