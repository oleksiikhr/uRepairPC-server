<?php

namespace App\Events\Users;

trait EModel
{
    public $roomName = 'users';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'users';
    }
}
