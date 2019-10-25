<?php

namespace App\Http\Middleware;

use Closure;

class ParentCheck
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
        if (! auth()->user()->is_kid) {
            return $next($request);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
