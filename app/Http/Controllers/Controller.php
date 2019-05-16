<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\IPermissions;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController implements IPermissions
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var int */
    const PAGINATE_DEFAULT = 30;

    public function __construct(Request $request)
    {
        $this->allowPermissions($this->permissions($request));
    }

    /**
     * Register middleware on depends key-value array.
     *  key - method
     *  value - list of permissions.
     *
     * @param array $roles
     */
    private function allowPermissions(array $roles)
    {
        $activeRoute = Route::getCurrentRoute();

        if (! $activeRoute || empty($roles)) {
            return;
        }

        $activeMethod = $activeRoute->getActionMethod();

        if (array_key_exists($activeMethod, $roles)) {
            $role = $roles[$activeMethod];
            $permissions = is_array($role) ? implode('|', $role) : $role;
            if (! empty($permissions)) {
                $this->middleware('permission:'.$permissions);
            }
        }
    }
}
