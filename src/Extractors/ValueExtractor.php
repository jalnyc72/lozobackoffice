<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Extractors;

interface ValueExtractor
{
    /**
     * @param mixed  $element
     * @param string $key
     *
     * @return string the extracted value
     */
    public function extract($element, string $key);
}
