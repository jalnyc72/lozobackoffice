<?php

namespace Digbang\Backoffice\Inputs;

use Carbon\Carbon;

/**
 * Class DateTime.
 */
class DateTime extends Input implements InputInterface
{
    protected $date;

    public function setValue($name, $value)
    {
        if (is_array($value)) {
            $value = implode(' ', $value);
        }

        if (is_string($value)) {
            $value = Carbon::parse($value);
        }

        parent::setValue($name, $value);
    }
}
