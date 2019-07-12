<?php

namespace App\Events\Equipments;

use App\Events\Common\ECreateBroadcast;

class ECreate extends ECreateBroadcast
{
    use EModel;

    /**
     * @return array|string|null
     */
    public function rooms()
    {
        return [
            $this->roomName,
            "{$this->roomName} [user_id.{$this->data['user_id']}]",
        ];
    }
}
