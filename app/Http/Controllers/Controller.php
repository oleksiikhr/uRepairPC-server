<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var int */
    const PAGINATE_DEFAULT = 50;

    /**
     * Register middleware on depends key-value array.
     *  key - method
     *  value - list of permissions
     *
     * @param array $roles
     */
    public function allowPermissions(array $roles)
    {
        $activeRoute = Route::getCurrentRoute();

        if (! $activeRoute) {
            return;
        }

        $activeMethod = $activeRoute->getActionMethod();

        if (array_key_exists($activeMethod, $roles)) {
            $role = $roles[$activeMethod];
            $permissions = is_array($role) ? join('|', $role) : $role;
            $this->middleware('permission:' . $permissions);
        }
    }
}
