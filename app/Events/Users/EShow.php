<?php

namespace App\Events\Users;

use App\User;
use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EShow extends EJoinBroadcast
{
    use EModel;

    public function __construct(User $model)
    {
        $rooms = ["{$this->roomName}.{$model->id}"];
        $user = auth()->user();

        if ($user->perm(Perm::ROLES_VIEW_ALL)) {
            $rooms[] = "{$this->roomName}.{$model->id}.roles";
        }

        parent::__construct($rooms, false);
    }
}
