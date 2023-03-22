<?php

namespace Digbang\Backoffice\Support;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class ContextResolver
{
    public const ENVIRONMENT_KEY = 'BACKOFFICE_CONTEXT';

    /** @var Application */
    private $app;
    /** @var Repository */
    private $config;
    /** @var string */
    private $defaultKey;
    /** @var array */
    private $contexts = [];

    public function __construct(Application $app, Repository $config, string $defaultKey)
    {
        $this->app = $app;
        $this->config = $config;
        $this->defaultKey = $defaultKey;

        $this->parseContexts();
    }

    public function getContexts(): array
    {
        return $this->contexts;
    }

    public function configKey($context): string
    {
        $configKey = $this->defaultKey;
        if ($this->defaultKey !== $context) {
            $configKey .= ".context.$context";
        }

        return $configKey;
    }

    public function getDefaultContext(): string
    {
        foreach ($this->contexts as $context) {
            if ($this->config->get("{$this->configKey($context)}.default_context", false)) {
                return $context;
            }
        }

        return $this->contexts[0];
    }

    public function getContextFromEnvironment(): string
    {
        $defaultContext = $this->getDefaultContext();

        if ($this->app->runningUnitTests() || $this->app->runningInConsole()) {
            $context = env(static::ENVIRONMENT_KEY);
        }

        $route = $this->app->request->getRequestUri();
        $contextIdentifier = array_values(array_filter(explode('/', parse_url($route, PHP_URL_PATH))));

        if ($contextIdentifier) {
            $contextIdentifier = $contextIdentifier[0];
            if (\in_array($contextIdentifier, $this->contexts, true)) {
                $context = $contextIdentifier;
            }
        }

        return $context ?? $defaultContext;
    }

    protected function parseContexts(): void
    {
        $contexts = $this->config->get($this->defaultKey, []);

        if (!isset($contexts['context'])) {
            throw new \InvalidArgumentException('Missing context configuration. You must have at least one context configuration.');
        }

        $this->contexts = array_keys($contexts['context']);
    }
}
