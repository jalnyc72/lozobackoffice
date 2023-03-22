<?php

namespace Digbang\Backoffice\Listings;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

interface RowAwareRenderable
{
    /**
     * @param Collection $row
     *
     * @return string|Renderable|\Illuminate\View\View
     */
    public function renderWith(Collection $row);
}
