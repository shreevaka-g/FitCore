<?php

namespace domain\Facades;

use Illuminate\Support\Facades\Facade;
use domain\Services\MemberService;

class MemberFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MemberService::class;
    }
}
