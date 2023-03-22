<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;

class View extends Input implements InputInterface
{
    /**
     * @var array
     */
    protected $with;

    public function __construct(ControlInterface $control, $name, array $with = [])
    {
        parent::__construct($control, $name);

        $this->setWith($with);
    }

    /**
     * {@inheritdoc}
     */
    public function changeName($name)
    {
        /** @var View $view */
        $view = parent::changeName($name);
        $view->setWith($this->with);

        return $view;
    }

    /**
     * @return array
     */
    public function with(): array
    {
        return $this->with;
    }

    /**
     * @param array $with
     */
    public function setWith(array $with)
    {
        $this->with = $with;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return parent::render()->with($this->with());
    }
}
