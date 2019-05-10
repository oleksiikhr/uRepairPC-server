<?php

namespace App\Events\EquipmentTypes;

use App\Events\Common\EUpdateBroadcast;

class EUpdate extends EUpdateBroadcast
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
