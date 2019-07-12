<?php

namespace App\Events\EquipmentModels;

trait EModel
{
    public $roomName = 'equipment_models';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_models';
    }
}
