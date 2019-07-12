<?php

namespace App\Events\Requests;

trait EModel
{
    public $roomName = 'requests';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'requests';
    }
}
