<?php

namespace App\Events\Equipments;

trait EModel
{
    public $roomName = 'equipments';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'equipments';
    }
}
