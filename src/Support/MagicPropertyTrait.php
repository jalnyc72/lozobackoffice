<?php

namespace Digbang\Backoffice\Support;

trait MagicPropertyTrait
{
    /**
     * @var array
     *
     * @internal
     */
    private $refMethodCache = [];

    /**
     * @param string $method
     *
     * @return \ReflectionMethod
     *
     * @internal
     */
    private function __getReflectionMethod($method)
    {
        if (!array_key_exists($method, $this->refMethodCache)) {
            $this->refMethodCache[$method] = new \ReflectionMethod($this, $method);
        }

        return $this->refMethodCache[$method];
    }

    /**
     * @param string $property
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     *
     * @api
     */
    public function __get($property)
    {
        foreach (['get', 'is'] as $prefix) {
            if (method_exists($this, $method = $prefix . studly_case($property))) {
                $reflectionMethod = $this->__getReflectionMethod($method);

                if ($reflectionMethod->isPublic()) {
                    return $reflectionMethod->invoke($this);
                }
            }
        }

        throw new \BadMethodCallException("Property '$property' is private or does not exist.");
    }
}
