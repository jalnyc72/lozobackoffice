<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Controls;

use Illuminate\Support\Collection;

interface ControlInterface
{
    /**
     * The view that will be rendered. Controls always render a view of some sort.
     *
     * @return string
     */
    public function view();

    /**
     * A text that will be printed to the user.
     *
     * @return string
     */
    public function label();

    /**
     * The control HTML options.
     *
     * @return array
     */
    public function options();

    /**
     * Access a specific HTML option.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function option(string $key);

    /**
     * @param string $view
     *
     * @return self
     */
    public function changeView($view);

    /**
     * @param string $label
     *
     * @return self
     */
    public function changeLabel($label);

    /**
     * @param array|Collection $options
     *
     * @return self
     */
    public function changeOptions($options);

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function changeOption(string $key, $value);

    /**
     * Check if the given class name exists on the control.
     *
     * @param string $className
     *
     * @return bool
     */
    public function hasClass(string $className): bool;

    /**
     * @return \Illuminate\View\View
     */
    public function render();
}
