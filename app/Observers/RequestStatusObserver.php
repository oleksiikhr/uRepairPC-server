<?php

namespace App\Observers;

use App\RequestStatus;
use App\Events\RequestStatuses\ECreate;
use App\Events\RequestStatuses\EDelete;
use App\Events\RequestStatuses\EUpdate;

class RequestStatusObserver
{
    /**
     * Handle the request status "created" event.
     *
     * @param  \App\RequestStatus  $requestStatus
     * @return void
     */
    public function created(RequestStatus $requestStatus)
    {
        event(new ECreate($requestStatus));
    }

    /**
     * Handle the request status "updated" event.
     *
     * @param  \App\RequestStatus  $requestStatus
     * @return void
     */
    public function updated(RequestStatus $requestStatus)
    {
        event(new EUpdate($requestStatus->id, $requestStatus));
    }

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
