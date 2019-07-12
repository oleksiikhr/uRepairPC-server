<?php

namespace App\Events\RequestFiles;

trait EModel
{
    public $roomName = 'request_files';

    /**
     * @return string
     */
    public function event(): string
    {
        return 'request_files';
    }
}
