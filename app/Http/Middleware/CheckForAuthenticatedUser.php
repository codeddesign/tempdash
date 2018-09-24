<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

/**
 * Class CheckForAuthenticatedUser
 * @package App\Http\Middleware
 */
class CheckForAuthenticatedUser
{
    /**
     * Checks to see that there is a currently logged in user in session, and if not, redirects
     * them to the login page.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::getCurrentUser()) {
            session()->flash('error', 'Your session has expired.');

            if (!$request->isXmlHttpRequest()) {
                // Send user to login screen
                return response()
                    ->redirectTo(route('auth_login', [], false) . '?whence=' . urlencode($request->path()));
            } else {
                return response()
                    ->json(['redirect_to' => route('auth_login', [], false), 'status' => 302], 302);
            }
        }

        return $next($request);
    }
}
