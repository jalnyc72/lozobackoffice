<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Controls;

use Illuminate\Support\Collection;
use Illuminate\View\Factory;

/**
 * Immutable implementation of the Control class.
 *
 * @implements ControlInterface
 */
final class Control implements ControlInterface
{
    /**
     * @var \Illuminate\View\Factory
     */
    private $viewFactory;

    /**
     * @var string
     */
    private $view;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $options;

    /**
     * @param Factory          $viewFactory
     * @param string           $view
     * @param string           $label
     * @param array|Collection $options
     */
    public function __construct(Factory $viewFactory, $view, $label, $options = [])
    {
        if (!$options instanceof Collection) {
            $options = new Collection($options);
        }

        $this->viewFactory = $viewFactory;
        $this->view = $view;
        $this->label = $label;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * {@inheritdoc}
     */
    public function label()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function option(string $key)
    {
        return $this->options->get($key, null);
    }

    /**
     * {@inheritdoc}
     */
    public function options()
    {
        return $this->options->all();
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass(string $className): bool
    {
        $classes = $this->option('class');

        return mb_strpos($classes, $className) !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function changeView($view)
    {
        return new self(
            $this->viewFactory,
            $view,
            $this->label,
            $this->options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function changeLabel($label)
    {
        return new self(
            $this->viewFactory,
            $this->view,
            $label,
            $this->options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function changeOptions($options)
    {
        return new self(
            $this->viewFactory,
            $this->view,
            $this->label,
            $options
        );
    }

    /**
     * {@inheritdoc}
     */
    public function changeOption(string $key, $value)
    {
        $options = $this->options->all();
        $options[$key] = $value;

        return $this->changeOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->viewFactory->make($this->view(), [
            'options' => $this->options(),
            'label'   => $this->label(),
        ]);
    }

    /**
     * Clone options so they are separated from the cloned control.
     */
    public function __clone()
    {
        $this->options = clone $this->options;
    }
}
