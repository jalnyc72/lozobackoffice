<?php

namespace Digbang\Backoffice\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Menu.
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menuFactory';
    }
}
