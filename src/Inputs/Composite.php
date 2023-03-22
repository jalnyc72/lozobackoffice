<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;

/**
 * Class Composite.
 */
class Composite extends Input implements InputInterface
{
    protected $inputCollection;

    public function __construct($name, ControlInterface $control, Collection $inputCollection)
    {
        $this->inputCollection = $inputCollection;

        parent::__construct($control, $name);
    }

    /**
     * @param string $name
     *
     * @return Composite
     */
    public function changeName($name)
    {
        return new self($name, $this->control, $this->inputCollection);
    }

    /**
     * The input value as received from the user.
     *
     * @return string It may well be an integer, but everything is stringy from HTTP requests...
     */
    public function value()
    {
        return $this->extract('value');
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return parent::render()->with([
            'inputs' => $this->inputCollection,
        ]);
    }

    /**
     * Check if the input matches the given name.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasName($name)
    {
        return !is_null($this->inputCollection->find($name));
    }

    /**
     * Sets the value of the input.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function setValue($name, $value)
    {
        if ($input = $this->inputCollection->find($name)) {
            /* @var $input InputInterface */
            $input->setValue($name, $value);
        }
    }

    protected function extract($key)
    {
        $output = [];
        foreach ($this->inputCollection as $input) {
            /* @var $input InputInterface */
            $output[$input->name()] = $input->{$key}();
        }

        return $output;
    }

    /**
     * Check if the given class name exists on the control.
     *
     * @param $className
     *
     * @return bool
     */
    public function hasClass(string $className): bool
    {
        return $this->control->hasClass($className);
    }

    public function isVisible()
    {
        return $this->inputCollection->getVisible()->count() > 0;
    }

    public function hide()
    {
        foreach ($this->inputCollection->getVisible() as $input) {
            $input->hide();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(): bool
    {
        return $this->inputCollection->hasFile();
    }
}
