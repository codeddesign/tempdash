<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckAccessControl
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
        if (!$request->isXmlHttpRequest()) {
            if ($user = Auth::getCurrentUser()) {
                $access_control = config('access_control');
                if ($user->role == 'User') {

                    $permissions = $user->permissions;

                    if (isset($access_control[$request->route()->getName()])) {
                        $permission = $access_control[$request->route()->getName()];
                        if (empty($permissions[$permission])) {
                            abort(401, 'You are not authorized to view this page.');
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
