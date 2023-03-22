<?php

namespace Digbang\Backoffice\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Link.
 */
class Link extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'linkMaker';
    }
}
