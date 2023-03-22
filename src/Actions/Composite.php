<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Digbang\Backoffice\Controls\ControlInterface;
use Illuminate\Contracts\Support\Renderable;

class Composite extends Collection implements ActionInterface, Renderable
{
    /**
     * @var ControlInterface
     */
    protected $control;

    /**
     * @var string|null
     */
    protected $icon;

    /**
     * @param ControlInterface               $control
     * @param ActionFactory                  $factory
     * @param \Illuminate\Support\Collection $collection
     * @param string|null                    $icon
     */
    public function __construct(ControlInterface $control, ActionFactory $factory, \Illuminate\Support\Collection $collection, $icon = null)
    {
        parent::__construct($factory, $collection);

        $this->control = $control;
        $this->icon = $icon;
    }

    /**
     * Composite actions don't use target, they trigger a javascript drop-down effect.
     *
     * @return string
     */
    public function target()
    {
        return '#';
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        foreach ($this->collection as $action) {
            if ($action->isActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->control->render()->with([
            'target'   => $this->target(),
            'actions'  => $this->collection,
            'icon'     => $this->icon(),
            'isActive' => $this->isActive(),
        ]);
    }
}
