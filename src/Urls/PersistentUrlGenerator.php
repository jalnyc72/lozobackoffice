<?php

namespace Digbang\Backoffice\Urls;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Session\Store;
use Illuminate\Support\Arr;

/**
 * Class PersistentUrl.
 */
class PersistentUrlGenerator implements UrlGenerator
{
    /**
     * @var Store
     */
    private $session;

    /**
     * @var UrlGenerator
     */
    private $url;

    /**
     * @var Router
     */
    private $router;

    public function __construct(UrlGenerator $url, Store $session, Router $router)
    {
        $this->session = $session;
        $this->url = $url;
        $this->router = $router;
    }

    /**
     * @param Route   $route
     * @param Request $request
     */
    public function persist(Route $route, Request $request)
    {
        if ($request->isMethod('GET')) {
            $path = $route->uri();
            $parsed = parse_url($path);

            $this->session->put(
                $this->getStoreKey(ltrim(Arr::get($parsed, 'path', $path), '/')),
                $request->all()
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $route = $this->router->getRoutes()->getByName($name);

        if ($route && $this->isPersistentUrl($route)) {
            $path = $this->url->route($name, $this->onlyRequiredParams($route, $parameters));

            $parameters = $this->getPersisted($path, $parameters);
        }

        return $this->url->route($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function action($action, $parameters = [], $absolute = true)
    {
        $route = $this->router->getRoutes()->getByAction($action);

        if ($route && $this->isPersistentUrl($route)) {
            $path = $this->url->action($action, $this->onlyRequiredParams($route, $parameters));

            $parameters = $this->getPersisted($path, $parameters);
        }

        return $this->url->action($action, $parameters, $absolute);
    }

    private function getPersisted($path, $parameters = [])
    {
        $parsed = parse_url($path);

        $key = $this->getStoreKey(ltrim(Arr::get($parsed, 'path', $path), '/'));

        if ($persisted = $this->session->get($key)) {
            return array_merge($persisted, $parameters);
        }

        return $parameters;
    }

    private function getStoreKey($name)
    {
        return "persistenturl_$name";
    }

    /**
     * {@inheritdoc}
     */
    public function to($path, $extra = [], $secure = null)
    {
        //The user is trying to redirect to a specific url. We don't want to override that.
        return $this->url->to($path, $extra, $secure);
    }

    /**
     * {@inheritdoc}
     */
    public function secure($path, $parameters = [])
    {
        return $this->url->secure($path, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function asset($path, $secure = null)
    {
        return $this->url->asset($path, $secure);
    }

    /**
     * {@inheritdoc}
     */
    public function setRootControllerNamespace($rootNamespace)
    {
        $this->url->setRootControllerNamespace($rootNamespace);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->url->current();
    }

    public function previous($fallback = false)
    {
        return $this->url->previous($fallback);
    }

    /**
     * Check if a given Route is persistent.
     *
     * @param Route $route
     *
     * @return bool
     */
    private function isPersistentUrl(Route $route)
    {
        return $this->findMiddleware($route->gatherMiddleware(), 'persistent');
    }

    private function findMiddleware($middlewares, string $search)
    {
        $middlewares = (array) $middlewares;

        if (\in_array($search, $middlewares, true)) {
            return true;
        }

        foreach ($middlewares as $middleware) {
            if ((is_string($middleware) || is_numeric($middleware)) && $this->router->hasMiddlewareGroup($middleware)) {
                return $this->findMiddleware($this->router->getMiddlewareGroups()[$middleware], $search);
            }
        }

        return false;
    }

    /**
     * Get only the required (named) parameters from a given route, based on the given parameters.
     *
     * @param Route $route
     * @param array $parameters
     *
     * @return array
     */
    private function onlyRequiredParams(Route $route, array $parameters)
    {
        $names = $route->parameterNames();

        return array_intersect_key($parameters, array_flip($names));
    }

    public function __call($name, $args)
    {
        if (\is_callable([$this->url, $name])) {
            return \call_user_func_array([$this->url, $name], $args);
        }
    }
}
