<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Extractors;

class ClosureValueExtractor implements ValueExtractor
{
    /**
     * @var callable
     */
    private $closure;

    /**
     * @param callable $closure
     */
    public function __construct(callable $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param mixed  $element
     * @param string $key
     *
     * @return string the extracted value
     */
    public function extract($element, string $key)
    {
        return call_user_func($this->closure, $element, $key);
    }
}
