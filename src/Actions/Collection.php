<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Countable;
use Illuminate\Support\Collection as LaravelCollection;
use IteratorAggregate;

class Collection implements ActionFactoryInterface, IteratorAggregate, Countable
{
    /**
     * @var ActionFactory
     */
    protected $factory;

    /**
     * @var LaravelCollection|Action[]
     */
    protected $collection;

    /**
     * @param ActionFactory     $factory
     * @param LaravelCollection $collection
     */
    public function __construct(ActionFactory $factory, LaravelCollection $collection)
    {
        $this->factory = $factory;
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function link($target, $label = null, $options = [], $view = 'backoffice::actions.link', $icon = null)
    {
        $link = $this->factory->link($target, $label, $options, $view, $icon);
        $this->collection->push($link);

        return $link;
    }

    /**
     * {@inheritdoc}
     */
    public function form($target, $label, $method = 'POST', $options = [], $view = null)
    {
        $form = $this->factory->form($target, $label, $method, $options, $view);
        $this->collection->push($form);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function dropdown($label, $options = [], $view = 'backoffice::actions.dropdown', $icon = null)
    {
        $this->collection->push($dropdown = $this->factory->dropdown($label, $options, $view, $icon));

        return $dropdown;
    }

    /**
     * @param ActionInterface $action
     *
     * @return $this
     */
    public function add(ActionInterface $action)
    {
        $this->collection->push($action);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function modal($id, $title, $form, $label, $options = [], $icon = null)
    {
        $modal = $this->factory->modal($id, $title, $form, $label, $options, $icon);
        $this->collection->push($modal);

        return $modal;
    }

    /**
     * {@inheritdoc}
     */
    public function collection()
    {
        return $this->factory->collection();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * @return ActionBuilderInterface
     */
    public function build()
    {
        return new ActionBuilderWrapper(
            new ActionBuilder($this->factory),
            $this
        );
    }
}
