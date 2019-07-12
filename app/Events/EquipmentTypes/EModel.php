<?php

namespace App\Events\EquipmentTypes;

trait EModel
{
    public $roomName = 'equipment_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_types';
    }
}
