<?php

namespace App\Services\Facades;

use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Facade;

class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthServiceInterface::class;
    }
}