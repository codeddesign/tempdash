<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateForApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['status' => 401, 'message' => 'X-API-KEY is not present or is invalid.']);
        } else {
            return $next($request);
        }
    }

    /**
     * Returns true if the API request passes the necessary security checks.
     *
     * @param Request $request
     * @return bool
     */
    private function isAuthorized(Request $request)
    {
        return ($request->header('X-API-KEY') == env('API_KEY'));
    }
}
