<?php

namespace App\Events\EquipmentManufacturers;

trait EModel
{
    public $roomName = 'equipment_manufacturers';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_manufacturers';
    }
}
