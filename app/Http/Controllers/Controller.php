<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Register middleware on depends key-value array.
     * Allow only accept routes on current role.
     *
     * @param  array  $data
     */
    public function allowRoles($data) {
        if (! Auth::check()) {
            $this->middleware('block.request');
            return;
        }

        $me = Auth::user();

        if (array_key_exists($me->role, $data)) {
            $this->middleware('block.request')->except($data[$me->role]);
        }
    }
}
