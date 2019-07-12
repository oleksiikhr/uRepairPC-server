<?php

namespace App\Events\Roles;

trait EModel
{
    public $roomName = 'roles';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'roles';
    }
}
