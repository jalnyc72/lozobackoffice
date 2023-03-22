<?php

namespace Digbang\Backoffice\Support;

use Digbang\Backoffice\Actions\Collection as ActionCollection;
use Digbang\Backoffice\Controls\ControlInterface;
use Illuminate\Contracts\Support\Renderable;

class Menu implements Renderable, \Countable
{
    protected $actionTree;
    protected $control;

    public function __construct(ControlInterface $control, ActionCollection $actionTree)
    {
        $this->control = $control;
        $this->actionTree = $actionTree;
    }

    /**
     * @param \Digbang\Backoffice\Actions\Collection $actionTree
     */
    public function setActionTree(ActionCollection $actionTree)
    {
        $this->actionTree = $actionTree;
    }

    /**
     * @return ActionCollection
     */
    public function getActionTree()
    {
        return $this->actionTree;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return $this->control->render()->with([
            'actionTree' => $this->actionTree,
        ]);
    }

    public function count()
    {
        $count = 0;

        foreach ($this->actionTree as $action) {
            if ($action instanceof ActionCollection) {
                $count += $action->count();
            } else {
                $count++;
            }
        }

        return $count;
    }

    public function isEmpty()
    {
        return $this->count() == 0;
    }
}
