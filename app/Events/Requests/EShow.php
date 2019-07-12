<?php

namespace App\Events\Requests;

use App\Request;
use App\Events\Common\EJoinBroadcast;

class EShow extends EJoinBroadcast
{
    use EModel;

    public function __construct(Request $model)
    {
        parent::__construct("{$this->roomName}.{$model->id}", false);
    }
}
