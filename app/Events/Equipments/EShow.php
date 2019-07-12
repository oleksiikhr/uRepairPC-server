<?php

namespace App\Events\Equipments;

use App\Equipment;
use App\Events\Common\EJoinBroadcast;

class EShow extends EJoinBroadcast
{
    use EModel;

    public function __construct(Equipment $model)
    {
        parent::__construct("{$this->roomName}.{$model->id}", false);
    }
}
