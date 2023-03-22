<?php

namespace Digbang\Backoffice\Actions;

/**
 * Class ActionBuilder.
 *
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 * @method $this addDataToggle($message)
 * @method $this addDataPlacement($message)
 * @method $this addTitle($message)
 */
interface ActionBuilderInterface
{
    /**
     * @param string|callable $target
     *
     * @return $this
     */
    public function to($target);

    /**
     * @param string|callable $label
     *
     * @return $this
     */
    public function labeled($label);

    /**
     * @param string|callable $view
     *
     * @return $this
     */
    public function view($view);

    /**
     * @param string|callable $icon
     *
     * @return $this
     */
    public function icon($icon);

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function add($attribute, $value);

    /**
     * @return Action
     */
    public function asLink();

    /**
     * @param string $method
     *
     * @return Action
     */
    public function asForm($method = 'POST');
}
