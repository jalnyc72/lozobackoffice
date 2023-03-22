<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Digbang\Backoffice\Controls\ControlInterface;

class Form extends Action
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @param ControlInterface $control
     * @param callable|string  $target
     * @param string           $method
     */
    public function __construct(ControlInterface $control, $target, $method = 'POST')
    {
        parent::__construct($control, $target);

        $this->method = $method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        if($rendered = parent::render())
        {
            return $rendered->with([
                'method' => $this->method(),
            ]);
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function renderWith($row)
    {
        if ($view = parent::renderWith($row)) {
            return $view->with(['method' => $this->method()]);
        }

        return $view;
    }
}
