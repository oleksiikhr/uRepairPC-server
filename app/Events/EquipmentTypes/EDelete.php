<?php

namespace App\Events\EquipmentTypes;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
