<?php

namespace App\Events\Users;

use App\Events\Common\EUpdateBroadcast;

class EUpdateRoles extends EUpdateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return "{$this->roomName}.{$this->id}.roles";
    }
}
