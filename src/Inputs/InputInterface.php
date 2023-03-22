<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;

interface InputInterface extends ControlInterface
{
    /**
     * The input name, as will be sent in the request.
     *
     * @return string
     */
    public function name();

    /**
     * Change the input name.
     * This method should return a new instance of the input, with the changed name applied,
     * to prevent aliasing issues.
     *
     * @param string $name
     *
     * @return InputInterface
     */
    public function changeName($name);

    /**
     * The input name, as will be sent in the request, transformed into dot notation.
     *
     * @return string
     */
    public function dottedName();

    /**
     * The input value as received from the user.
     *
     * @return string It may well be an integer, but everything is stringy from HTTP requests...
     */
    public function value();

    /**
     * The input default value, used before the user interacts.
     *
     * @param $value
     */
    public function defaultsTo($value);

    /**
     * Check if the input matches the given name.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasName($name);

    /**
     * Sets the value of the input matching the given name.
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function setValue($name, $value);

    /**
     * @return bool
     */
    public function isVisible();

    /**
     * @return void
     */
    public function hide();

    /**
     * @param bool $required
     *
     * @return void
     */
    public function setRequired(bool $required = true): void;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @return bool
     */
    public function isFile(): bool;
}
