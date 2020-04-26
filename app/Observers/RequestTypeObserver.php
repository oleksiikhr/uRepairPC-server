<?php

namespace App\Observers;

use App\Events\RequestTypes\EDelete;
use App\RequestType;

class RequestTypeObserver
{
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
