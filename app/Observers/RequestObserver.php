<?php

namespace App\Observers;

use App\Events\Requests\EDelete;
use App\Request;

class RequestObserver
{
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
