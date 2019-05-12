<?php

namespace App\Events\EquipmentTypes;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_types';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'equipment_types';
    }
}
