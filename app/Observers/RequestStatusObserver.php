<?php

namespace App\Observers;

use App\Events\RequestStatuses\EDelete;
use App\RequestStatus;

class RequestStatusObserver
{
    /**
     * Handle the request status "deleted" event.
     *
     * @param  \App\RequestStatus  $requestStatus
     * @return void
     */
    public function deleted(RequestStatus $requestStatus)
    {
        event(new EDelete($requestStatus));
    }
}
