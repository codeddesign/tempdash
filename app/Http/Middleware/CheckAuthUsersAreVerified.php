<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

/**
 * Class CheckAuthUsersAreVerified
 * @package App\Http\Middleware
 */
class CheckAuthUsersAreVerified
{
    /**
     * Checks if the currently logged in user's email has been verified, that they have a password,
     * that they have been admin approved, ect. and if not, redirects them to the appropriate page.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = Auth::getCurrentUser()) {
            if (!$user->is_email_verified) {
                return (!$request->isXmlHttpRequest()) ? response()->redirectToRoute('user_email_verification') :
                    response()->json(['redirect_to' => route('user_email_verification', [], false)], 302);
            } elseif (empty($user->password)) {
                return (!$request->isXmlHttpRequest()) ? response()->redirectToRoute('auth_set_password') :
                    response()->json(['redirect_to' => route('auth_set_password', [], false)], 302);
            } elseif (!$user->is_verified_by_admin) {
                return (!$request->isXmlHttpRequest()) ? response()->redirectToRoute('user_admin_verify_notice') :
                    response()->json(['redirect_to' => route('user_admin_verify_notice', [], false)], 302);
            }
        }

        return $next($request);
    }
}
