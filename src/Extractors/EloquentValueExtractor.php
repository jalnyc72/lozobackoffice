<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Extractors;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EloquentValueExtractor.
 */
class EloquentValueExtractor implements ValueExtractor
{
    /**
     * @param mixed  $element
     * @param string $key
     *
     * @return string
     */
    public function extract($element, string $key)
    {
        if (!$element instanceof Model) {
            throw new \UnexpectedValueException("Given object must be an Eloquent\\Model.");
        }

        $output = $element->{$key};

        if ($output instanceof Carbon) {
            return (string) $output;
        }

        return $output;
    }
}
