<?php

namespace App\Events\EquipmentModels;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
{
    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipment_models';
    }

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return 'equipment_models';
    }
}
