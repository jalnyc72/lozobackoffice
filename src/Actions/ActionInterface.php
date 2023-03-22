<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

interface ActionInterface
{
    /**
     * The target URL this action points to.
     *
     * @return string|callable
     */
    public function target();

    /**
     * Is the action the current active URL?
     *
     * @return bool
     */
    public function isActive();
}
