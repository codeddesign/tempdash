<?php

namespace App\Http\Controllers;

use App\Mail\PasswordRecovery;
use App\Models\AppUser;
use Authy\AuthyResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Mail;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * GET set new password.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setNewPassword()
    {
        return view('auth.set_new_password',
            [
                'do_set_password_link' => route('auth_do_set_password', [], false),
                'page_title' => 'Set Password'
            ]);
    }

    /**
     * GET login page.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(Request $request)
    {
        return view('auth.login', [
            'login_url' => route('auth_do_login', [], false),
            'whence' => $request->get('whence'),
            'page_title' => 'Sign In'
        ]);
    }

    /**
     * GET password recovery screen.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordRecovery()
    {
        return view('auth.password_recovery', ['page_title' => 'Password Recovery']);
    }


    /**
     * POST send email to user to recover password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function doPasswordRecovery(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $app_user = AppUser::whereRaw('lower(email) = lower(?)', [$request->get('email')])->first();
        if (!($app_user instanceof AppUser))
            throw new UnauthorizedHttpException('Unauthorized');

        $app_user->generateAndSaveNewToken();

        Mail::to($request->get('email'))->send(new PasswordRecovery($app_user));
        return response()->json(['error' => false]);
    }

    /**
     * GET logout user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::forgetCurrentUser();
        return response()->redirectToRoute('home');
    }

    /**
     * GET two factor auth form.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function twoFactor(Request $request, int $id) {
        return view('auth.two_factor_form', ['id' => $id, 'whence' => '/' . $request->query('whence')]);
    }


    /**
     * POST do two factor token check.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function doTwoFactorCheck(Request $request, int $id) {
        try {
            $result = Auth::getAuthyApi()->verifyToken($id, $request->get('code'));

            // If it is valid, log the user in
            if ($result->ok()) {
                Auth::setCurrentUser(AppUser::where('authy_id', '=', $id)->first());
            }

            return response()->json(['is_valid' => $result->ok(), 'redirect' => '']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * POST resend two factor verification code.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function doResendTwoFactorCode(Request $request, int $id) {

        /** @var AuthyResponse $authy_res */
        $authy_res = Auth::getAuthyApi()->requestSms($id, ['force' => true]);

        if ($authy_res->ok()) {
            return response()->json([
                'error' => false
            ]);
        } else {
            throw new \Exception($authy_res->message());
        }
    }

    /**
     * POST authenticate the email and password from login form.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function doAuthentication(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Auth::authenticate($request->get('email'), $request->get('password'));
        if (!($user instanceof AppUser)) {
            // User is not authenticated
            return response()->json(['error' => 'The email and/or password is incorrect.'], 401);
        }

        // Send SMS validation code
        if (config('business.do_two_factor') && !empty($user->authy_id)) {

            /** @var AuthyResponse $authy_res */
            $authy_res = Auth::getAuthyApi()->requestSms($user->authy_id, ['force' => true]);

            if ($authy_res->ok()) {
                return response()->json([
                    'error' => false,
                    'redirect' => true, 'to' =>
                        route('auth_two_factor', [$user->authy_id], false) . ($request->get('whence') ? '?whence=' . urlencode($request->get('whence'))  : '')
                ]);
            } else {
                throw new \Exception($authy_res->message());
            }

        } else if (empty($user->authy_id)) {
            // TODO: Log the fact that the user does not have an authy id.
        }

        Auth::setCurrentUser($user);
        $user->recent_login_time = Carbon::now();
        $user->save();

        return response()->json(['error' => null, 200]);
    }

    /**
     * POST update password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doSetPassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'confirm_password' => 'same:password'
        ]);

        // Get user and update their password
        /** @var AppUser $app_user */
        $app_user = AppUser::findOrFail(session()->get('user_id'));
        $app_user->update([
            'password' => bcrypt($request->get('password'))
        ]);

        $app_user->addActivity('Updated password.')->save();

        session()->flash('flash-success', 'Your password has been successfully set.');
        return response()->json(['error' => false]);
    }

    /**
     * GET initialize password recovery and redirect user to password reset.
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initPasswordRecovery(string $token)
    {
        // Check token to see if user can be found
        $app_user = AppUser::where('token', '=', $token)->first();
        if (!($app_user instanceof AppUser) || !$app_user->validateToken($token))
        {
            // Invalid token
            if ($app_user instanceof AppUser) {
                $app_user->token = null;
                $app_user->token_expiry = null;
                $app_user->save();
            }

            session()->flash('flash-error', 'The token received for password reset was invalid or has expired.');
            return response()->redirectToRoute('auth_login');
        }

        // Redirect user to password recovery
        $app_user->token = null;
        $app_user->token_expiry = null;
        $app_user->password = null;
        $app_user->save();

        Auth::setCurrentUser($app_user);
        return response()->redirectToRoute('auth_set_password');
    }
}