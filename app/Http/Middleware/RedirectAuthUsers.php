<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

/**
 * Class RedirectAuthUsers
 * @package App\Http\Middleware
 */
class RedirectAuthUsers
{
    /**
     * Checks if a user is logged in, it redirects them back to the home page,
     * which would ultimately be their dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::getCurrentUser()) {
            return response()->redirectToRoute('home');
        }

        return $next($request);
    }
}
