<?php
namespace Digbang\Backoffice\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor()
    {
        return 'backofficeform';
    }
}
