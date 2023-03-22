<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Support\WrapsUI;
use Illuminate\Contracts\View\Factory;

class Filter extends InputDecorator
{
    use WrapsUI;

    /** @var Factory */
    private $viewFactory;

    /** @var string */
    private $size = 'col-sm-3';

    /** @var bool */
    private $isVisible = false;

    public function __construct(Factory $viewFactory, InputInterface $wrapped)
    {
        parent::__construct($wrapped);

        $this->viewFactory = $viewFactory;

        $this->changeWrappingView('backoffice::inputs.filter');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return $this->viewFactory->make($this->wrappingView, [
            'size'      => $this->size,
            'isVisible' => $this->isVisible(),
            'label'     => $this->label(),
            'name'      => $this->name(),
            'value'     => $this->value(),
            'input'     => $this->wrapped,
        ]);
    }

    /**
     * @param string $name
     *
     * @return Filter
     */
    public function changeName($name)
    {
        return new self($this->viewFactory, $this->wrapped->changeName($name));
    }

    public function setSize(string $size)
    {
        $this->size = $size;

        return $this;
    }

    public function size()
    {
        return $this->size;
    }

    public function setVisible(bool $visible)
    {
        $this->isVisible = $visible;

        return $this;
    }

    public function isVisible()
    {
        return $this->isVisible;
    }
}
