<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Controls;

use Illuminate\View\Factory;

class ControlFactory
{
    /**
     * @var Factory
     */
    protected $viewFactory;

    /**
     * @param Factory $viewFactory
     */
    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * @param string $view
     * @param string $label
     * @param array  $options
     *
     * @return Control
     */
    public function make($view, $label, $options = [])
    {
        return new Control($this->viewFactory, $view, $label, $options);
    }

    /**
     * @return Factory
     */
    public function getViewFactory()
    {
        return $this->viewFactory;
    }
}
