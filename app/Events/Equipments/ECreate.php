<?php

namespace App\Events\Equipments;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipments';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'equipments';
    }
}
