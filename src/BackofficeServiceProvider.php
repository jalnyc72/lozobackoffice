<?php

namespace Digbang\Backoffice;

use Digbang\Backoffice\Forms\FormBuilder;
use Digbang\Backoffice\Repositories\DoctrineUserRepository;
use Digbang\Backoffice\Support\ContextResolver;
use Digbang\Backoffice\Support\MenuFactory;
use Digbang\Backoffice\Urls\PersistentUrlGenerator;
use Digbang\Backoffice\Urls\PersistentUrlGeneratorExtension;
use Digbang\Backoffice\Urls\PersistentUrlMiddleware;
use Digbang\Fonts\FontsServiceProvider;
use Digbang\Security\Configurations\SecurityContextConfiguration;
use Digbang\Security\Laravel\SecurityServiceProvider;
use Digbang\Security\Permissions\InsecurePermissionRepository;
use Digbang\Security\Permissions\PermissionRepository;
use Digbang\Security\SecurityContext;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Maatwebsite\Excel\ExcelServiceProvider;

class BackofficeServiceProvider extends ServiceProvider
{
    public const VENDOR_KEY = 'backoffice';

    public function register()
    {
        $this->app->register(FontsServiceProvider::class);
        $this->app->register(ExcelServiceProvider::class);
        $this->app->register(SecurityServiceProvider::class);

        $this->app->singleton(ContextResolver::class, function(Application $app) {
            return new ContextResolver(
                $app,
                $app->make(Repository::class),
                static::VENDOR_KEY
            );
        });

        $this->app->singleton('linkMaker', Support\LinkMaker::class);
        $this->app->singleton('backofficeform', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->token());
            return $form->setSessionStore($app['session.store']);
        });

        $this->configureContexts();
    }

    public function boot(
        Repository      $config,
        Router          $router,
        Str             $str,
        Support\Str     $myStr,
        SecurityContext $securityContext,
        Factory         $view,
        ContextResolver $contextResolver
    ) {

        $this->publish();
        $this->addPersistentUrlGenerator($router);
        $this->addMacros($str, $myStr);
        $this->addMiddlewares($router);

        $this->addContexts($securityContext, $config, $contextResolver);
        $this->addComposers($view, $contextResolver);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['backofficeform', FormBuilder::class];
    }

    protected function configureContexts(): void
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        /** @var ContextResolver $contextResolver */
        $contextResolver = $this->app->make(ContextResolver::class);

        foreach ($contextResolver->getContexts() as $context) {
            $configKey = $contextResolver->configKey($context);

            $this->mergeConfigFrom(dirname(__DIR__) . '/config/' . static::VENDOR_KEY . '.php', $configKey);
            $this->app->singleton("{$context}_menuFactory", $this->getMenuFactoryClass($config, $configKey));
        }

        $this->app->singleton(PermissionRepository::class, function (Container $container) use ($contextResolver, $config) {
            $contextKey = $contextResolver->getContextFromEnvironment();

            return $container->make($config->get("$contextKey.auth.permissions.repository") ?: InsecurePermissionRepository::class);
        });
    }

    protected function mergeConfigFrom($path, $key)
    {
        $configKey = $key;
        if ($key === static::VENDOR_KEY) {
            $configKey .= '.context.' . static::VENDOR_KEY;
        }

        $systemConfig = $this->app['config']->get($configKey, []);
        $default = require $path;

        $this->app['config']->set($key, array_merge($default['context'][static::VENDOR_KEY], $systemConfig));
    }

    private function publish(): void
    {
        $root = realpath(dirname(__DIR__));

        $this->publishes(
            ["$root/config/" . static::VENDOR_KEY . '.php' => config_path(static::VENDOR_KEY . '.php')],
            'config'
        );

        $this->loadViewsFrom("$root/resources/views", static::VENDOR_KEY);
        $this->publishes(
            ["$root/resources/views" => base_path('resources/views/vendor/' . static::VENDOR_KEY)],
            'views'
        );

        $this->loadTranslationsFrom("$root/resources/lang", static::VENDOR_KEY);
        $this->publishes(
            ["$root/resources/lang" => base_path('resources/lang/vendor/' . static::VENDOR_KEY)],
            'lang'
        );

        $this->publishes([
            "$root/public/css"    => public_path('vendor/' . static::VENDOR_KEY . '/css'),
            "$root/public/fonts"  => public_path('vendor/' . static::VENDOR_KEY . '/fonts'),
            "$root/public/images" => public_path('vendor/' . static::VENDOR_KEY . '/images'),
            "$root/public/js"     => public_path('vendor/' . static::VENDOR_KEY . '/js'),
        ], 'assets');
    }

    private function addPersistentUrlGenerator(Router $router)
    {
        $url = new PersistentUrlGenerator(
            $this->app->make(UrlGeneratorContract::class),
            $this->app->make(Store::class),
            $router
        );

        $this->app->instance(UrlGeneratorContract::class, $url);

        $this->app->bind(UrlGenerator::class, function (Container $container) use ($url) {
            $extension = $container->make(PersistentUrlGeneratorExtension::class);
            $extension->setUrlGenerator($url);

            return $extension;
        });

        $this->app->alias(UrlGenerator::class, 'url');
    }

    private function addMacros(Str $str, Support\Str $myStr): void
    {
        $str->macro('titleFromSlug', [$myStr, 'titleFromSlug']);
        $str->macro('parse', [$myStr, 'parse']);
    }

    private function addMiddlewares(Router $router): void
    {
        $router->aliasMiddleware('persistent', PersistentUrlMiddleware::class);
    }

    private function addContexts(SecurityContext $securityContext, Repository $config, ContextResolver $contextResolver): void
    {
        foreach ($contextResolver->getContexts() as $context) {
            $configKey = $contextResolver->configKey($context);

            $configuration = new SecurityContextConfiguration($context);

            $configuration->setLoginRoute($config->get("$configKey.auth.login-route", "$configKey.auth.login"));
            $configuration->setPrefix($config->get("$configKey.auth.global_table_prefix"));

            $configuration->setUserTable(
                $config->get("$configKey.auth.users.custom_table") ?: 'users'
            );

            $configuration->changeUsers(DoctrineUserRepository::class);

            $repository = $config->get("$configKey.auth.users.custom_repository");
            $mapping = $config->get("$configKey.auth.users.custom_mapping");

            if ($repository) {
                $configuration->changeUsers($repository, $mapping);
            } elseif ($mapping) {
                throw $this->repositoryException(
                    \Digbang\Security\Users\DoctrineUserRepository::class
                );
            }

            if (!$config->get("$configKey.auth.roles.enabled")) {
                $configuration->disableRoles();
            } else {
                $configuration->setRoleTable(
                    $config->get("$configKey.auth.roles.custom_table") ?: 'roles'
                );

                $configuration->setUsersRolesTable(
                    $config->get("$configKey.auth.roles.custom_join_table") ?: 'user_role'
                );

                $repository = $config->get("$configKey.auth.roles.custom_repository");
                $mapping = $config->get("$configKey.auth.roles.custom_mapping");

                if ($repository) {
                    $configuration->changeRoles($repository, $mapping);
                } elseif ($mapping) {
                    throw $this->repositoryException(
                        \Digbang\Security\Roles\DoctrineRoleRepository::class
                    );
                }
            }

            if (!$config->get("$configKey.auth.permissions.enabled")) {
                $configuration->disablePermissions();
            } else {
                $configuration->setUserPermissionTable(
                    $config->get("$configKey.auth.permissions.custom_user_permission_table") ?: 'user_permissions'
                );

                $configuration->setRolePermissionTable(
                    $config->get("$configKey.auth.permissions.custom_role_permission_table") ?: 'role_permissions'
                );

                $userPermissionMapping = $config->get("$configKey.auth.permissions.custom_user_permission_mapping");
                $rolePermissionMapping = $config->get("$configKey.auth.permissions.custom_role_permission_mapping");

                if ($userPermissionMapping || $rolePermissionMapping) {
                    $configuration->changePermissions($userPermissionMapping, $rolePermissionMapping);
                }

                $configuration->setPermissionRepository(
                    $config->get("$configKey.auth.permissions.repository") ?: InsecurePermissionRepository::class
                );
            }

            $configuration->setActivationTable(
                $config->get("$configKey.auth.activations.custom_table") ?: 'activations'
            );

            if ($lottery = $config->get("$configKey.auth.activations.lottery")) {
                $configuration->setActivationsLottery($lottery);
            }

            if ($expiration = $config->get("$configKey.auth.activations.expiration")) {
                $configuration->setActivationsExpiration($expiration);
            }

            $repository = $config->get("$configKey.auth.activations.custom_repository");
            $mapping = $config->get("$configKey.auth.activations.custom_mapping");

            if ($repository) {
                $configuration->changeActivations($repository, $mapping);
            } elseif ($mapping) {
                throw $this->repositoryException(
                    \Digbang\Security\Activations\DoctrineActivationRepository::class
                );
            }

            $configuration->setReminderTable(
                $config->get("$configKey.auth.reminders.custom_table") ?: 'reminders'
            );

            if ($lottery = $config->get("$configKey.auth.reminders.lottery")) {
                $configuration->setRemindersLottery($lottery);
            }

            if ($expiration = $config->get("$configKey.auth.reminders.expiration")) {
                $configuration->setRemindersExpiration($expiration);
            }

            $repository = $config->get("$configKey.auth.reminders.custom_repository");
            $mapping = $config->get("$configKey.auth.reminders.custom_mapping");

            if ($repository) {
                $configuration->changeReminders($repository, $mapping);
            } elseif ($mapping) {
                throw $this->repositoryException(
                    \Digbang\Security\Reminders\DoctrineReminderRepository::class
                );
            }

            if ($config->get("$configKey.auth.persistences.single")) {
                $configuration->setSinglePersistence();
            }

            $configuration->setPersistenceTable(
                $config->get("$configKey.auth.persistences.custom_table") ?: 'persistences'
            );

            $repository = $config->get("$configKey.auth.persistences.custom_repository");
            $mapping = $config->get("$configKey.auth.persistences.custom_mapping");

            if ($repository) {
                $configuration->changePersistences($repository, $mapping);
            } elseif ($mapping) {
                throw $this->repositoryException(
                    \Digbang\Security\Persistences\DoctrinePersistenceRepository::class
                );
            }

            if (!$config->get("$configKey.auth.throttles.enabled")) {
                $configuration->disableThrottles();
            } else {
                $configuration->setThrottleTable(
                    $config->get("$configKey.auth.throttles.custom_table") ?: 'throttles'
                );

                $repository = $config->get("$configKey.auth.throttles.custom_repository");

                if ($repository) {
                    $configuration->changeThrottles($repository, [
                        'throttle'       => $config->get("$configKey.auth.throttles.custom_mappings.custom_throttle_mapping"),
                        'ipThrottle'     => $config->get("$configKey.auth.throttles.custom_mappings.custom_ip_throttle_mapping"),
                        'globalThrottle' => $config->get("$configKey.auth.throttles.custom_mappings.custom_global_throttle_mapping"),
                        'userThrottle'   => $config->get("$configKey.auth.throttles.custom_mappings.custom_user_throttle_mapping"),
                    ]);
                } elseif (array_filter($config->get("$configKey.auth.throttles.custom_mappings"), 'strlen')) {
                    throw $this->repositoryException(\Digbang\Security\Throttling\DoctrineThrottleRepository::class);
                }
            }

            $securityContext->add($configuration);
        }
    }

    /**
     * Add view composers.
     *
     * @param Factory $view
     * @param ContextResolver $contextResolver
     */
    private function addComposers(Factory $view, ContextResolver $contextResolver): void
    {
        $context = $contextResolver->getContextFromEnvironment();
        $contextKey = $contextResolver->configKey($context);

        $view->composer('backoffice::menu', function (\Illuminate\View\View $view) use ($context, $contextKey) {
            $menuFactory = $this->app->make("{$context}_menuFactory");

            $view->with([
                'menus' => $menuFactory->make("$contextKey.menu"),
            ]);
        });

        $view->composer('*', function (\Illuminate\View\View $view) use ($context, $contextKey) {
            $view->with([
                'context' => $context,
                'contextKey' => $contextKey,
            ]);
        });
    }

    /**
     * Repository mapping exception, refactored here for ease of use.
     *
     * @param string $baseRepositoryName
     *
     * @return \InvalidArgumentException
     */
    private function repositoryException($baseRepositoryName): \InvalidArgumentException
    {
        return new \InvalidArgumentException(
            'Cannot use custom mappings without a custom repository. ' .
            "Please extend $baseRepositoryName with your custom implementation."
        );
    }

    private function getMenuFactoryClass(Repository $config, $context): string
    {
        return $config->get("$context.menu_factory", MenuFactory::class);
    }
}
