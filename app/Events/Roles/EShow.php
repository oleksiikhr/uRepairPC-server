<?php

namespace App\Events\Roles;

use App\Role;
use App\Events\Common\EJoinBroadcast;

class EShow extends EJoinBroadcast
{
    use EModel;

    public function __construct(Role $model)
    {
        parent::__construct("{$this->roomName}.{$model->id}", false);
    }
}
