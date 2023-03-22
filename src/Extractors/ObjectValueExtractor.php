<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Extractors;

use BadMethodCallException;
use ReflectionMethod;
use ReflectionProperty;

class ObjectValueExtractor implements ValueExtractor
{
    /**
     * @var ReflectionMethod[][]
     */
    private $refMethodCache = [];

    /**
     * @var ReflectionProperty[][]
     */
    private $refPropertyCache = [];

    /**
     * @param object $object
     * @param string $method
     *
     * @return ReflectionMethod
     */
    private function reflectMethod($object, string $method): ReflectionMethod
    {
        $class = get_class($object);

        if (!array_key_exists($class, $this->refMethodCache)) {
            $this->refMethodCache[$class] = [];
        }

        if (!array_key_exists($method, $this->refMethodCache[$class])) {
            $this->refMethodCache[$class][$method] = new ReflectionMethod($class, $method);
        }

        return $this->refMethodCache[$class][$method];
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return ReflectionProperty
     */
    private function reflectProperty($object, string $property): ReflectionProperty
    {
        $class = get_class($object);

        if (!array_key_exists($class, $this->refPropertyCache)) {
            $this->refPropertyCache[$class] = [];
        }

        if (!array_key_exists($property, $this->refPropertyCache[$class])) {
            $this->refPropertyCache[$class][$property] = new ReflectionProperty($class, $property);
        }

        return $this->refPropertyCache[$class][$property];
    }

    /**
     * @param object $element
     * @param string $key
     *
     * @throws BadMethodCallException
     *
     * @return string the extracted value
     */
    public function extract($element, string $key)
    {
        if (property_exists($element, $key) && $this->reflectProperty($element, $key)->isPublic()) {
            return $element->$key;
        }

        $studly = studly_case($key);

        foreach (["get$studly", "is$studly", $key, lcfirst($studly)] as $method) {
            if (method_exists($element, $method)) {
                $method = $this->reflectMethod($element, $method);

                if ($method->isPublic()) {
                    return $method->invoke($element);
                }
            }
        }

        throw new BadMethodCallException("Property [$key] is private, has no getter or does not exist.");
    }
}
