<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;

/**
 * Class DropDown.
 */
class DropDown extends Input implements InputInterface
{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var array
     */
    protected $dataAttributes = [];
    /**
     * @var DropDown
     */
    protected $parent;
    /**
     * @var string
     */
    protected $dataRoute;

    public function __construct(ControlInterface $control, $name, $value = null, $data = [])
    {
        parent::__construct($control, $name, $value);

        $this->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function changeName($name)
    {
        $dropDown = parent::changeName($name);
        $dropDown->data = $this->data;
        $dropDown->dataAttributes = $this->dataAttributes;
        $dropDown->parent = $this->parent;
        $dropDown->dataRoute = $this->dataRoute;

        return $dropDown;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param array $dataAttributes
     */
    public function setDataAttributes(array $dataAttributes)
    {
        $this->dataAttributes = $dataAttributes;
    }

    /**
     * @return array
     */
    public function dataAttributes(): array
    {
        return $this->dataAttributes;
    }

    public function setValue($name, $value)
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        parent::setValue($name, $value);
    }

    /**
     * @param InputInterface $parent
     * @param string $route
     * @return DropDown
     */
    public function nested(InputInterface $parent, string $route)
    {
        $this->parent = $parent;
        $this->dataRoute = $route;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        if($this->parent)
        {
            $this->changeOption('data-nested-parent', $this->parent->option('id'));
            $this->changeOption('data-nested-route', $this->dataRoute);
            $this->changeOption('data-nested-value', old($this->name(), $this->value()));
        }

        return parent::render()->with([
            'data' => $this->data(),
            'dataAttributes' => $this->dataAttributes(),
        ]);
    }
}
