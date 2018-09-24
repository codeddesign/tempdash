<?php

namespace App\Services;

use App\Services\Contracts\AuthServiceInterface;
use Authy\AuthyApi;
use Illuminate\Contracts\Session\Session;
use Hash;
use App\Models\AppUser;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService implements AuthServiceInterface
{
    /** @var Session */
    protected $session_service;

    /** @var \App\Models\AppUser */
    protected $current_user;

    /** @var AuthyApi */
    protected $authy_api;

    /**
     * AuthService constructor.
     * @param Session $sessionService
     */
    public function __construct(Session $sessionService) {
        $this->session_service = $sessionService;
        $this->authy_api = new AuthyApi(env('AUTHY_API_KEY'));
    }

    /**
     * Authenticate an email and password.
     *
     * @param string $email
     * @param string $password
     * @return \App\Models\AppUser
     */
    public function authenticate(string $email, string $password) {
        $user = AppUser::whereRaw('lower(email) = lower(?) AND is_inactive != ?', [
            $email, true
        ])->first();

        if (($user instanceof AppUser) && Hash::check($password, $user->password)) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Set the current signed in user.
     *
     * @param \App\Models\AppUser $app_user
     */
    public function setCurrentUser(AppUser $app_user) {
        $this->current_user = $app_user;
        $this->session_service->put('user_id', $app_user->id);
    }

    /**
     * Return the current signed in user.
     *
     * @return \App\Models\AppUser
     */
    public function getCurrentUser() {
        if (!$this->current_user && $this->session_service->get('user_id')) {
            if (!empty($app_user = AppUser::find($this->session_service->get('user_id'))) && !$app_user->is_inactive) {
                $this->current_user = $app_user;
            } else {

                // The user cannot be found
                if ($this->session_service->get('user_id')) {
                    $this->forgetCurrentUser();
                    $this->session_service->flash('flash-error', 'The current logged in user cannot be found.');
                }
            }
        }

        return $this->current_user;
    }

    /**
     * Log out the current signed in user.
     */
    public function forgetCurrentUser() {
        $this->session_service->forget('user_id');
        $this->current_user = null;
    }

    /**
     * Gets the Authy API object.
     *
     * @return AuthyApi
     */
    public function getAuthyApi(): AuthyApi
    {
        return $this->authy_api;
    }
}