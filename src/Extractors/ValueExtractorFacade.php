<?php

namespace Digbang\Backoffice\Extractors;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueExtractorFacade.
 */
class ValueExtractorFacade implements ValueExtractor
{
    /**
     * @var array
     */
    protected $extractors = [];

    /**
     * Flyweight instances.
     *
     * @var array
     */
    protected $instances = [];

    /**
     * @param mixed  $element
     * @param string $key
     *
     * @return string the extracted value
     */
    public function extract($element, string $key)
    {
        $extractor = $this->getExtractorFor($key, $element);

        return $extractor->extract($element, $key);
    }

    /**
     * @param string                  $key
     * @param callable|ValueExtractor $extractor
     *
     * @throws \InvalidArgumentException
     */
    public function add($key, $extractor)
    {
        if (is_callable($extractor)) {
            $extractor = new ClosureValueExtractor($extractor);
        }

        if (!$extractor instanceof ValueExtractor) {
            throw new \InvalidArgumentException("Extractors must implement " . ValueExtractor::class . " or be callable.");
        }

        $this->addExtractor($key, $extractor);
    }

    /**
     * @param string         $key
     * @param ValueExtractor $extractor
     */
    protected function addExtractor($key, ValueExtractor $extractor)
    {
        $this->extractors[$key] = $extractor;
    }

    /**
     * @param string $key
     * @param mixed  $element
     *
     * @return ValueExtractor
     */
    protected function getExtractorFor($key, $element)
    {
        switch (true) {
            case array_key_exists($key, $this->extractors):
                return $this->extractors[$key];
            case $element instanceof Model:
                return $this->eloquentExtractor();
            case is_array($element):
                return $this->arrayExtractor();
            case is_object($element):
                return $this->objectExtractor();
        }

        throw new \UnexpectedValueException("Unable to extract values from given element. " .
            "Elements may be arrays (or Arrayable / ArrayAccess objects), objects with get{PropName} / is{BoolName} conventions " .
            "or a specific extractor must be set to the given key [$key]."
        );
    }

    /**
     * @return EloquentValueExtractor
     */
    protected function eloquentExtractor()
    {
        if (!array_key_exists('eloquent', $this->instances)) {
            $this->instances['eloquent'] = new EloquentValueExtractor();
        }

        return $this->instances['eloquent'];
    }

    /**
     * @return ArrayValueExtractor
     */
    protected function arrayExtractor()
    {
        if (!array_key_exists('array', $this->instances)) {
            $this->instances['array'] = new ArrayValueExtractor();
        }

        return $this->instances['array'];
    }

    /**
     * @return ObjectValueExtractor
     */
    protected function objectExtractor()
    {
        if (!array_key_exists('object', $this->instances)) {
            $this->instances['object'] = new ObjectValueExtractor();
        }

        return $this->instances['object'];
    }
}
