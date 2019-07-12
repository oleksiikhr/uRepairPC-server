<?php

namespace App\Events\Equipments;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EIndex extends EJoinBroadcast
{
    use EModel;

    public function __construct()
    {
        $user = auth()->user();
        $rooms = [];

        if ($user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
            $rooms[] = $this->roomName;
        }

        if ($user->perm(Perm::EQUIPMENTS_VIEW_OWN)) {
            $rooms[] = "{$this->roomName} [user_id.{$user->id}]";
        }

        parent::__construct($rooms, false);
    }
}
