<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Extractors;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use UnexpectedValueException;

class ArrayValueExtractor implements ValueExtractor
{
    /**
     * @param array|ArrayAccess|Arrayable $element
     * @param string                      $key
     *
     * @throws UnexpectedValueException
     *
     * @return string
     */
    public function extract($element, string $key)
    {
        return $this->getValue($this->convert($element), $key);
    }

    /**
     * @param mixed $element
     *
     * @throws UnexpectedValueException
     *
     * @return array
     */
    protected function convert($element)
    {
        if (!is_array($element) && !($element instanceof ArrayAccess) && !($element instanceof Arrayable)) {
            throw new UnexpectedValueException('Cannot extract from this: '.gettype($element));
        }

        return $element instanceof Arrayable ? $element->toArray() : $element;
    }

    /**
     * @param array|ArrayAccess $element
     * @param string            $key
     *
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    protected function getValue($element, string $key)
    {
        if (!isset($element[$key])) {
            throw new UnexpectedValueException("Key [$key] does not exist in the given array.");
        }

        return $element[$key];
    }
}
