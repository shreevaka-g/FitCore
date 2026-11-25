<?php

namespace domain\Facades;

use Illuminate\Support\Facades\Facade;
use domain\Services\MembershipPlanService;

class MembershipPlanFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MembershipPlanService::class;
    }
}
