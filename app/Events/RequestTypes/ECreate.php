<?php

namespace App\Events\RequestTypes;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return self::$roomName;
    }

    /**
     * @return string
     */
    protected function join(): string
    {
        return self::$roomName . ".{$this->data['id']}";
    }
}
