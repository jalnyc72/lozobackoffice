<?php

namespace Digbang\Backoffice\Support;

use Illuminate\Contracts\Support\Arrayable;

class Collection extends \Illuminate\Support\Collection
{
    public function mergeInto($items)
    {
        if ($items instanceof self) {
            $items = $items->all();
        } elseif ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        $this->items = array_merge($this->items, $items);

        return $this;
    }
}
