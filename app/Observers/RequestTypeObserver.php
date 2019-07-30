<?php

namespace App\Observers;

use App\RequestType;
use App\Events\RequestTypes\ECreate;
use App\Events\RequestTypes\EDelete;
use App\Events\RequestTypes\EUpdate;

class RequestTypeObserver
{
    /**
     * Handle the request type "created" event.
     *
     * @param  \App\RequestType  $requestType
     * @return void
     */
    public function created(RequestType $requestType)
    {
        event(new ECreate($requestType));
    }

    /**
     * Handle the request type "updated" event.
     *
     * @param  \App\RequestType  $requestType
     * @return void
     */
    public function updated(RequestType $requestType)
    {
        event(new EUpdate($requestType->id, $requestType));
    }

    /**
     * Handle the request type "deleted" event.
     *
     * @param  \App\RequestType  $requestType
     * @return void
     */
    public function deleted(RequestType $requestType)
    {
        event(new EDelete($requestType));
    }
}
