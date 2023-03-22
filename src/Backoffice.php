<?php

namespace Digbang\Backoffice;

use Digbang\Backoffice\Actions\ActionFactory;
use Digbang\Backoffice\Controls\ControlFactory;
use Digbang\Backoffice\Forms\FormFactory;
use Digbang\Backoffice\Forms\TranslatableFormFactory;
use Digbang\Backoffice\Listings\ColumnCollection;
use Digbang\Backoffice\Listings\ListingFactory;
use Digbang\Backoffice\Support\Breadcrumb;
use Digbang\Security\Exceptions\SecurityException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Collection;

/**
 * The Backoffice class represents an entry point to multiple backoffice object factories.
 */
class Backoffice
{
    /**
     * @var ListingFactory
     */
    private $listingFactory;

    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ControlFactory
     */
    private $controlFactory;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var UrlGenerator
     */
    private $urls;

    /**
     * @param ListingFactory $listingFactory
     * @param ActionFactory  $actionFactory
     * @param ControlFactory $controlFactory
     * @param FormFactory    $formFactory
     * @param UrlGenerator   $urls
     */
    public function __construct(ListingFactory $listingFactory, ActionFactory $actionFactory, ControlFactory $controlFactory, FormFactory $formFactory, UrlGenerator $urls)
    {
        $this->listingFactory = $listingFactory;
        $this->actionFactory = $actionFactory;
        $this->controlFactory = $controlFactory;
        $this->formFactory = $formFactory;
        $this->urls = $urls;
    }

    /**
     * Expose the ActionFactory object for complex action creation.
     *
     * @return ActionFactory
     */
    public function getActionFactory()
    {
        return $this->actionFactory;
    }

    /**
     * Expose the ControlFactory for custom controls.
     *
     * @return ControlFactory
     */
    public function getControlFactory()
    {
        return $this->controlFactory;
    }

    /**
     * Expose the FormFactory for custom form creation.
     *
     * @return FormFactory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * Expose the ListingFactory for custom listing creation.
     *
     * @return ListingFactory
     */
    public function getListingFactory()
    {
        return $this->listingFactory;
    }

    /**
     * Expose the UrlGenerator.
     *
     * @return UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->urls;
    }

    /**
     * Construct a listing object.
     *
     * @param array $columns
     *
     * @return Listings\Listing
     */
    public function listing($columns = [])
    {
        return $this->listingFactory->make(new ColumnCollection($columns));
    }

    /**
     * Construct a breadcrumb with an array of label => route.
     *
     * @param array  $data
     * @param string $label
     * @param array  $options
     *
     * @return Breadcrumb
     */
    public function breadcrumb($data = [], $label = '', $options = [])
    {
        $current = array_pop($data);

        try {
            $routes = [];
            foreach ($data as $text => $route) {
                if (!is_string($text)) {
                    $routes[] = $route;
                } else {
                    if (!is_string($route) || mb_strpos($route, '//') === false) {
                        $route = call_user_func_array([$this->urls, 'route'], (array) $route);
                    }

                    $routes[$text] = $route;
                }
            }

            $routes[] = $current;

            return new Breadcrumb(
                $this->controlFactory->make('backoffice::breadcrumb', $label, $options),
                new Collection($routes)
            );
        } catch (SecurityException $e) {
            // Discard the first one
            array_shift($data);

            return $this->breadcrumb($data + [$current], $label, $options);
        }
    }

    /**
     * Construct a collection of actions.
     *
     * @return Actions\Collection
     */
    public function actions()
    {
        return $this->actionFactory->collection();
    }

    /**
     * Construct a Form object.
     *
     * @param string $target
     * @param string $label
     * @param string $method
     * @param string $cancelAction
     * @param array  $options
     *
     * @return Forms\Form
     */
    public function form($target, $label, $method = 'POST', $cancelAction = '', $options = [])
    {
        return $this->formFactory->make($target, $label, $method, $cancelAction, $options);
    }

    /**
     * Get a TranslatableFormFactory for the given languages.
     *
     * @param string[] $languages
     *
     * @return Forms\Form|Forms\TranslatableFormFactory
     */
    public function translatable(array $languages)
    {
        return new TranslatableFormFactory(
            $this->formFactory,
            $this->controlFactory->getViewFactory(),
            $languages
        );
    }
}
