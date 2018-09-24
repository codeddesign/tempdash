<?php

namespace App\Services\Contracts;

use App\Models\AppUser;
use Authy\AuthyApi;

/**
 * Interface AuthServiceInterface
 * @package App\Services\Contracts
 */
interface AuthServiceInterface
{
    /**
     * Return the current signed in user.
     *
     * @return \App\Models\AppUser
     */
    public function getCurrentUser();

    /**
     * Set the current signed in user.
     *
     * @param \App\Models\AppUser $app_user
     */
    public function setCurrentUser(AppUser $app_user);

    /**
     * Authenticate an email and password.
     *
     * @param string $email
     * @param string $password
     * @return \App\Models\AppUser
     */
    public function authenticate(string $email, string $password);

    /**
     * Log out the current signed in user.
     */
    public function forgetCurrentUser();

    /**
     * Gets the Authy API object.
     *
     * @return AuthyApi
     */
    public function getAuthyApi(): AuthyApi;
}