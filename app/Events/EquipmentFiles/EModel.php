<?php

namespace App\Events\EquipmentFiles;

trait EModel
{
    public $roomName = 'equipment_files';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_files';
    }
}
