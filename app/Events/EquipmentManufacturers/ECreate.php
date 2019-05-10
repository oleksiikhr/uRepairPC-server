<?php

namespace App\Events\EquipmentManufacturers;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_manufacturers';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'equipment_manufacturers';
    }
}
