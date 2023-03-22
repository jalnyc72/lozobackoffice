<?php

namespace Digbang\Backoffice\Urls;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Psr\Log\LoggerInterface;

/**
 * Class PersistentUrlFilter.
 */
class PersistentUrlMiddleware
{
    /**
     * @var PersistentUrlGenerator
     */
    private $persistentUrl;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param PersistentUrlGenerator $persistentUrl
     * @param LoggerInterface        $logger
     */
    public function __construct(PersistentUrlGenerator $persistentUrl, LoggerInterface $logger)
    {
        $this->persistentUrl = $persistentUrl;
        $this->logger = $logger;
    }

    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $this->tryPersist($request->route(), $request);

        return $next($request);
    }

    private function tryPersist(Route $route, Request $request)
    {
        try {
            $this->persistentUrl->persist($route, $request);
        } catch (\Exception $e) {
            // Log error but don't prevent execution.
            $this->logger->error(
                "Unable to persist request parameters. Error message: " . $e->getMessage(),
                $e->getTrace()
            );
        }
    }
}
