<?php

namespace Jowusu837\HubtelUssd;

use Illuminate\Support\Facades\Facade as BaseFacade;


class Facade extends BaseFacade
{
    protected static function getFacadeAccessor()
    {
        return 'HubtelUssd';
    }
}