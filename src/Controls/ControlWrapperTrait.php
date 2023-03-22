<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Controls;

use Illuminate\Support\Collection;

trait ControlWrapperTrait
{
    /**
     * @var ControlInterface
     */
    protected $control;

    /**
     * @return string
     */
    public function view()
    {
        return $this->control->view();
    }

    /**
     * @return string
     */
    public function label()
    {
        return $this->control->label();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function option(string $key)
    {
        return $this->control->option($key);
    }

    /**
     * @return array
     */
    public function options()
    {
        return $this->control->options();
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function hasClass(string $className): bool
    {
        return $this->control->hasClass($className);
    }

    /**
     * @param string $view
     *
     * @return void
     */
    public function changeView($view)
    {
        $this->control = $this->control->changeView($view);
    }

    /**
     * @param string $label
     *
     * @return void
     */
    public function changeLabel($label)
    {
        $this->control = $this->control->changeLabel($label);
    }

    /**
     * @param array|Collection $options
     *
     * @return void
     */
    public function changeOptions($options)
    {
        $this->control = $this->control->changeOptions($options);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function changeOption(string $key, $value)
    {
        $this->control = $this->control->changeOption($key, $value);
    }
}
