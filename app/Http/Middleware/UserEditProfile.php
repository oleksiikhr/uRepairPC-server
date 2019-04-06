<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Permissions;
use Illuminate\Support\Facades\Auth;

class UserEditProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->can(Permissions::USERS_EDIT)) {
            return $next($request);
        }

        if ($user->can(Permissions::PROFILE_EDIT) && $user->id === (int)$request->user) {
            return $next($request);
        }

        return response()->json(['message' => __('app.middleware.no_permission')], 403);
    }
}
