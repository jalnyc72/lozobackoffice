<?php
use Digbang\Backoffice\Backoffice;
use Digbang\Backoffice\Support\PermissionParser;
use Digbang\Backoffice\Urls\BestUrlFinder;
use Digbang\Security\Contracts\SecurityApi;
use Digbang\Security\SecurityContext;
use Maatwebsite\Excel\Excel;

if (!function_exists('backoffice')) {
    /**
     * @return Backoffice
     */
    function backoffice()
    {
        return app(Backoffice::class);
    }
}

if (!function_exists('security')) {
    /**
     * @param string $context
     * @return SecurityApi
     */
    function security($context = 'backoffice')
    {
        return app(SecurityContext::class)->getSecurity($context);
    }
}

if (!function_exists('excel')) {
    /**
     * @return Excel
     */
    function excel()
    {
        return app(Excel::class);
    }
}

if (!function_exists('permission_parser')) {
    function permission_parser()
    {
        return app(PermissionParser::class);
    }
}

if (!function_exists('best_url')) {
    function best_url()
    {
        /** @var \Digbang\Backoffice\Support\ContextResolver $contextResolver */
        $contextResolver = app(\Digbang\Backoffice\Support\ContextResolver::class);

        return new BestUrlFinder(security($contextResolver->getContextFromEnvironment())->url());
    }
}

if (!function_exists('best_route')) {
    /**
     * @param array $routes
     * @return null|string
     */
    function best_route(array $routes)
    {
        return best_url()->bestRoute($routes);
    }
}

if (!function_exists('best_action')) {
    /**
     * @param array $actions
     * @return null|string
     */
    function best_action(array $actions)
    {
        return best_url()->bestAction($actions);
    }
}

if (!function_exists('best_path')) {
    /**
     * @param array $paths
     * @return null|string
     */
    function best_path(array $paths)
    {
        return best_url()->bestPath($paths);
    }
}

