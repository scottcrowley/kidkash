<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizedToUpdate
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
        if (
            (
                ! auth()->user()->is_kid
                && in_array(
                    auth()->user()->email,
                    config('kidkash.parents')
                )
            )
            || $request->route()->parameters()['user']->id == auth()->user()->id) {
            return $next($request);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
