<?php

namespace App\Events\Equipments;

use App\Events\Common\EDeleteBroadcast;

class EDelete extends EDeleteBroadcast
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
        return 'equipments.'.$this->id;
    }
}
