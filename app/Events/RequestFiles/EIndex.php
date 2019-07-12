<?php

namespace App\Events\RequestFiles;

use App\Enums\Perm;
use App\Events\Common\EJoinBroadcast;

class EIndex extends EJoinBroadcast
{
    use EModel;

    /**
     * Create a new event instance.
     *
     * @param  int  $requestId
     * @return void
     */
    public function __construct(int $requestId)
    {
        $user = auth()->user();
        $rooms = [];

        if ($user->perm(Perm::REQUESTS_FILES_VIEW_ALL)) {
            $rooms[] = "{$this->roomName}.{$requestId}";
        }

        if ($user->perm(Perm::REQUESTS_FILES_VIEW_OWN)) {
            $rooms[] = "{$this->roomName}.{$requestId} [user_id.{$user->id}]";
        }

        parent::__construct($rooms, false);
    }
}
