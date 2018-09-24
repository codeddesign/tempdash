<?php

namespace App\Services\Facades;

use App\Services\Contracts\SearchServiceInterface;
use Illuminate\Support\Facades\Facade;

class Search extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SearchServiceInterface::class;
    }
}