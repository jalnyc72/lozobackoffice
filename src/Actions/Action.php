<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Digbang\Backoffice\Controls\ControlInterface;
use Digbang\Backoffice\Controls\ControlWrapperTrait;
use Digbang\Backoffice\Support\EvaluatorTrait;
use Digbang\Security\Exceptions\SecurityException;
use Illuminate\Support\Collection as LaravelCollection;

class Action implements ActionInterface, ControlInterface
{
    use ControlWrapperTrait;
    use EvaluatorTrait;

    /**
     * @var string|callable
     */
    protected $target;

    /**
     * @var string|callable
     */
    protected $icon;

    /**
     * @var bool
     */
    protected $isActive = false;

    /**
     * @param ControlInterface     $control
     * @param string|callable      $target
     * @param string|callable|null $icon
     */
    public function __construct(ControlInterface $control, $target, $icon = null)
    {
        $this->control = $control;
        $this->target = $target;
        $this->icon = $icon;
    }

    /**
     * @param string|callable $target
     *
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string|callable
     */
    public function target()
    {
        return $this->target;
    }

    /**
     * @param string|callable $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string|callable
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * @param bool $isActive
     *
     * @return void
     */
    public function setActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return $this->renderTarget(
            $this->control,
            $this->target(),
            $this->icon(),
            $this->isActive()
        );
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\View\View|string
     */
    public function renderWith($row)
    {
        try {
            /** @var ControlInterface $control */
            $control = clone $this->control;

            $rowAsCollection = new LaravelCollection($row);

            $target = $this->evaluate($this->target(), $rowAsCollection);
            $icon = $this->evaluate($this->icon(), $rowAsCollection);
            $isActive = $this->evaluate($this->isActive(), $rowAsCollection);

            $label = $control->label();
            if (($newLabel = $this->evaluate($label, $rowAsCollection)) !== $label) {
                $control = $control->changeLabel($newLabel);
            }

            $view = $control->view();
            if (($newView = $this->evaluate($view, $rowAsCollection)) !== $view) {
                $control = $control->changeView($newView);
            }

            $options = $control->options();
            foreach ($options as $key => $option) {
                if ($option instanceof \Closure) {
                    $control = $control->changeOption($key, $option($rowAsCollection));
                }
            }

            return $this->renderTarget($control, $target, $icon, $isActive);
        } catch (SecurityException $e) {
            return '';
        }
    }

    /**
     * @param ControlInterface     $control
     * @param bool|string|callable $target
     * @param string|callable|null $icon
     * @param bool                 $isActive
     *
     * @return string|\Illuminate\View\View
     */
    protected function renderTarget(ControlInterface $control, $target, $icon, $isActive)
    {
        if ($target === false) {
            return '';
        }

        return $control->render()->with([
            'target'   => $target,
            'icon'     => $icon,
            'isActive' => $isActive,
        ]);
    }
}
