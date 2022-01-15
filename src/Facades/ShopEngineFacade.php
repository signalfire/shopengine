<?php

namespace Signalfire\Shopengine\Facades;

use Illuminate\Support\Facades\Facade;

class ShopEngineFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shopengine';
    }
}
