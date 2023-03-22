<?php

namespace Digbang\Backoffice\Inputs;

abstract class InputDecorator implements InputInterface
{
    /** @var InputInterface */
    protected $wrapped;

    public function __construct(InputInterface $wrapped)
    {
        $this->wrapped = $wrapped;
    }

    /**
     * {@inheritdoc}
     */
    public function view()
    {
        return $this->wrapped->view();
    }

    /**
     * {@inheritdoc}
     */
    public function label()
    {
        return $this->wrapped->label();
    }

    /**
     * {@inheritdoc}
     */
    public function options()
    {
        return $this->wrapped->options();
    }

    /**
     * {@inheritdoc}
     */
    public function option(string $key)
    {
        return $this->wrapped->option($key);
    }

    /**
     * {@inheritdoc}
     */
    public function changeView($view)
    {
        return $this->wrapped->changeView($view);
    }

    /**
     * {@inheritdoc}
     */
    public function changeLabel($label)
    {
        return $this->wrapped->changeLabel($label);
    }

    /**
     * {@inheritdoc}
     */
    public function changeOptions($options)
    {
        return $this->wrapped->changeOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function changeOption(string $key, $value)
    {
        return $this->wrapped->changeOption($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function hasClass(string $className): bool
    {
        return $this->wrapped->hasClass($className);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->wrapped->render();
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return $this->wrapped->name();
    }

    /**
     * {@inheritdoc}
     */
    public function changeName($name)
    {
        return $this->wrapped->changeName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function dottedName()
    {
        return $this->wrapped->dottedName();
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->wrapped->value();
    }

    /**
     * {@inheritdoc}
     */
    public function defaultsTo($value)
    {
        return $this->wrapped->defaultsTo($value);
    }

    /**
     * {@inheritdoc}
     */
    public function hasName($name)
    {
        return $this->wrapped->hasName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($name, $value)
    {
        $this->wrapped->setValue($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function isVisible()
    {
        return $this->wrapped->isVisible();
    }

    /**
     * {@inheritdoc}
     */
    public function hide()
    {
        $this->wrapped->hide();
    }

    /**
     * {@inheritdoc}
     */
    public function setRequired(bool $required = true): void
    {
        $this->wrapped->setRequired($required);
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): bool
    {
        return $this->wrapped->isRequired();
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(): bool
    {
        return $this->wrapped->isFile();
    }
}
