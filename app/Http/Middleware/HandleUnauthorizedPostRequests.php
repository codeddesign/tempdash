<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class HandleUnauthorizedPostRequests
 * @package App\Http\Middleware
 */
class HandleUnauthorizedPostRequests
{
    /**
     * Checks to see if there is a currently logged in user for POST
     * requests, and if there is not, for Async operations, returns a 401 status error
     * and for non-async requests, redirects them to the home page, which would redirect them to the
     * login screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::getCurrentUser())
        {
            if ($request->isXmlHttpRequest()) {
                // Throw an exception that will prompt JS to redirect
                throw new UnauthorizedHttpException('Unauthorized');
            } else {
                session()->flash('flash-error', 'The current logged in user cannot be found.');
                return response()->redirectToRoute('auth_login');
            }
        }

        return $next($request);
    }
}
