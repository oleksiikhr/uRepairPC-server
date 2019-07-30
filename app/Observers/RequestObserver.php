<?php

namespace App\Observers;

use App\Request;
use App\Events\Requests\ECreate;
use App\Events\Requests\EDelete;
use App\Events\Requests\EUpdate;

class RequestObserver
{
    /**
     * Handle the request "created" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function created(Request $request)
    {
        event(new ECreate($request));
    }

    /**
     * Handle the request "updated" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function updated(Request $request)
    {
        event(new EUpdate($request->id, $request));
    }

    /**
     * Handle the request "deleted" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function deleted(Request $request)
    {
        event(new EDelete($request));
    }
}
