<?php

namespace App\Observers;

use App\RequestPriority;
use App\Events\RequestPriorities\ECreate;
use App\Events\RequestPriorities\EDelete;
use App\Events\RequestPriorities\EUpdate;

class RequestPriorityObserver
{
    /**
     * Handle the request priority "created" event.
     *
     * @param  \App\RequestPriority  $requestPriority
     * @return void
     */
    public function created(RequestPriority $requestPriority)
    {
        event(new ECreate($requestPriority));
    }

    /**
     * Handle the request priority "updated" event.
     *
     * @param  \App\RequestPriority  $requestPriority
     * @return void
     */
    public function updated(RequestPriority $requestPriority)
    {
        event(new EUpdate($requestPriority->id, $requestPriority));
    }

    /**
     * Handle the request priority "deleted" event.
     *
     * @param  \App\RequestPriority  $requestPriority
     * @return void
     */
    public function deleted(RequestPriority $requestPriority)
    {
        event(new EDelete($requestPriority));
    }
}
