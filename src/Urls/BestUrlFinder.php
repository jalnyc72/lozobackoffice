<?php

namespace Digbang\Backoffice\Urls;

use Digbang\Security\Exceptions\SecurityException;
use Illuminate\Contracts\Routing\UrlGenerator;

class BestUrlFinder
{
    /**
     * @var UrlGenerator
     */
    private $urls;

    /**
     * BestUrlFinder constructor.
     *
     * @param UrlGenerator $urls
     */
    public function __construct(UrlGenerator $urls)
    {
        $this->urls = $urls;
    }

    /**
     * Try each route in order, return the first one that the
     * current user has permission to access.
     *
     * @param array $routes
     *
     * @return string|null
     */
    public function bestRoute(array $routes)
    {
        return $this->best('route', $routes);
    }

    /**
     * Try each action in order, return the first one that the
     * current user has permission to access.
     *
     * @param array $actions
     *
     * @return string|null
     */
    public function bestAction(array $actions)
    {
        return $this->best('action', $actions);
    }

    /**
     * Try each path in order, return the first one that the
     * current user has permission to access.
     *
     * @param array $paths
     *
     * @return string|null
     */
    public function bestPath(array $paths)
    {
        return $this->best('to', $paths);
    }

    /**
     * @param string $method
     * @param array  $routes
     *
     * @throws \InvalidArgumentException
     *
     * @return string|null
     */
    private function best($method, array $routes)
    {
        if (!method_exists($this->urls, $method)) {
            throw new \InvalidArgumentException("Method '$method' does not exist.");
        }

        foreach ($routes as $route) {
            if (!is_array($route)) {
                $route = [$route];
            }

            try {
                return call_user_func_array([$this->urls, $method], $route);
            } catch (SecurityException $e) {
                // Do nothing
            }
        }

        return null;
    }
}
