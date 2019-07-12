<?php

namespace App\Events\RequestTypes;

trait EModel
{
    public $roomName = 'request_types';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_types';
    }
}
