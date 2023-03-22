<?php

namespace Digbang\Backoffice\Support;

use Digbang\Backoffice\Actions\ActionFactory;
use Digbang\Backoffice\Actions\Collection as ActionCollection;
use Digbang\Backoffice\Controls\ControlFactory;
use Digbang\Security\Exceptions\SecurityException;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Class MenuFactory.
 */
class MenuFactory
{
    /**
     * @var array
     */
    protected $menu;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var ControlFactory
     */
    protected $controlFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var UrlGenerator
     */
    protected $urls;

    /**
     * @param ActionFactory  $actionFactory
     * @param ControlFactory $controlFactory
     * @param Config         $config
     * @param UrlGenerator   $urls
     */
    public function __construct(ActionFactory $actionFactory, ControlFactory $controlFactory, Config $config, UrlGenerator $urls)
    {
        $this->actionFactory = $actionFactory;
        $this->controlFactory = $controlFactory;
        $this->config = $config;
        $this->urls = $urls;
    }

    public function make(string $configKey): array
    {
        if (!$this->menu) {
            $this->menu = [];

            $menus = $this->config->get($configKey);

            foreach ($menus as $title => $menu) {
                $actionTree = $this->actionFactory->collection();

                foreach ($menu as $label => $config) {
                    $this->buildActionTree($actionTree, $label, $config);
                }

                $menu = $this->buildMenu($title, $actionTree);
                if (!$menu->isEmpty()) {
                    $this->menu[] = $menu;
                }
            }
        }

        return $this->menu;
    }

    /**
     * Build the Menu object with a title and a recursive collection of actions.
     *
     * @param string           $title
     * @param ActionCollection $actionTree
     *
     * @return Menu
     */
    protected function buildMenu($title, ActionCollection $actionTree)
    {
        return new Menu(
            $this->controlFactory->make(
                'backoffice::menu.main',
                $title,
                ['class' => 'nav nav-pills nav-stacked nav-bracket']),
            $actionTree);
    }

    /**
     * Build a recursive collection of actions based on a given configuration.
     *
     * @param ActionCollection $root
     * @param string           $label
     * @param string|array     $config
     *
     * @return ActionCollection
     */
    protected function buildActionTree(ActionCollection $root, $label, $config)
    {
        if (!is_array($config)) {
            $config = ['path' => $config];
        }

        if (array_key_exists('children', $config)) {
            $this->iterateChildren($root, $label, $config['children'], array_get($config, 'icon'));
        } else {
            $this->addLink($root, $label, $config);
        }

        return $root;
    }

    /**
     * Parse the configuration array and return a url.
     *
     * @param array $config
     *
     * @throws SecurityException         if the given UrlGenerator is aware of permissions.
     * @throws \InvalidArgumentException if the given config is malformed.
     *
     * @return string
     */
    protected function getUrlFromConfig(array $config)
    {
        $cleanPersisted = $config['clean-persisted'] ?? true;

        if (array_key_exists('path', $config)) {
            $url = $this->urls->to($config['path']);

            if ($cleanPersisted) {
                $url = $this->cleanPersistedUrl($url);
            }

            return $url;
        }

        if (array_key_exists('route', $config)) {
            list($route, $params) = array_pad((array) $config['route'], 2, []);

            $url = $this->urls->route($route, $params);

            if ($cleanPersisted) {
                $url = $this->cleanPersistedUrl($url);
                $url .= '?' . http_build_query($params);
            }

            return $url;
        }

        if (array_key_exists('action', $config)) {
            $url = $this->urls->action($config['action']);

            if ($cleanPersisted) {
                $url = $this->cleanPersistedUrl($url);
            }

            return $url;
        }

        throw new \InvalidArgumentException("Invalid menu configuration. Each configuration item needs either a url (in the form of 'path', 'route' or 'action') or an array of 'children'.");
    }

    protected function cleanPersistedUrl(string $url)
    {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            $url = str_replace([$parts['query'], '?'], '', $url);
        }

        return $url;
    }

    /**
     * Add a link to the given root collection.
     * Skips adding it if a SecurityException occurs.
     *
     * @param ActionCollection $root
     * @param string           $label
     * @param array            $config
     */
    protected function addLink(ActionCollection $root, $label, array $config)
    {
        try {
            $root->link($this->getUrlFromConfig($config), $label, [], 'backoffice::menu.link', array_get($config, 'icon'));
        } catch (SecurityException $e) {
            // Don't add it, user has no permission to see this
        }
    }

    /**
     * Iterate over an array of children items and add them recursively to the given root collection.
     *
     * @param ActionCollection $root
     * @param string           $label
     * @param array            $children
     * @param string           $icon
     */
    protected function iterateChildren(ActionCollection $root, $label, $children, $icon = null)
    {
        $branch = $root->dropdown($label, ['class' => 'nav-parent'], 'backoffice::menu.dropdown', $icon);

        foreach ($children as $leafLabel => $config) {
            $this->buildActionTree($branch, $leafLabel, $config);
        }
    }
}
