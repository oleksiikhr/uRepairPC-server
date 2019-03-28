<?php

namespace App\Http\Middleware;

use Closure;

class BlockRequest
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
        return response()->json(['message' => __('app.middleware.no_permission')], 403);
    }
}
