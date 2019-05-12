<?php

namespace App\Events\Equipments;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
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
        return 'equipments.' . $this->id;
    }
}
