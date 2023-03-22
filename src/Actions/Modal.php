<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Digbang\Backoffice\Controls\ControlInterface;
use Illuminate\Support\Collection as LaravelCollection;

class Modal extends Action
{
    /**
     * @var string|callable
     */
    private $id;
    /**
     * @var string|callable
     */
    private $title;
    /**
     * @var \Digbang\Backoffice\Forms\Form|callable
     */
    protected $form;

    /**
     * @param string|callable $id
     * @param string|callable $title
     * @param \Digbang\Backoffice\Forms\Form|callable $form
     * @param ControlInterface                        $control
     * @param string|callable|null                    $icon
     */
    public function __construct($id, $title, $form, ControlInterface $control, $icon = null)
    {
        parent::__construct($control, '#', $icon);

        $this->id = $id;
        $this->title = $title;
        $this->form = $form;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $id = $this->cleanId($this->resolve($this->id));
        $title = $this->resolve($this->title);

        $form = $this->resolve($this->form);
        if (!$form) {
            // If the callback returns false, we don't render!
            return '';
        }

        return parent::render()->with([
            'id'    => $id,
            'title' => $title,
            'form'  => $form,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function renderWith($row)
    {
        if ($rendered = parent::renderWith($row)) {

            $row = new LaravelCollection($row);

            $id = $this->cleanId($this->resolve($this->id, $row));
            $title = $this->resolve($this->title, $row);

            $form = $this->resolve($this->form, $row);
            if (!$form) {
                // If the callback returns false, we don't render!
                return '';
            }

            return $rendered->with([
                'id'    => $id,
                'title' => $title,
                'form'  => $form,
            ]);
        }

        return $rendered;
    }

    private function cleanId($id)
    {
        //If the id has a dot, jquery cannot get the element from the DOM, as it searches for an element like #id.className
        return str_replace('.', '_', $id);
    }

    private function resolve($property, $arguments = null)
    {
        if ($property instanceof \Closure) {
            return $property($arguments);
        }

        return $property;
    }
}
