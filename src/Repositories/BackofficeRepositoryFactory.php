<?php

namespace Digbang\Backoffice\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BackofficeRepositoryFactory.
 */
class BackofficeRepositoryFactory
{
    public function makeForEloquentModel(Model $eloquent)
    {
        return new EloquentBackofficeRepository($eloquent);
    }
}
