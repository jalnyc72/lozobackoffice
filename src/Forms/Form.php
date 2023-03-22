<?php

namespace Digbang\Backoffice\Forms;

use Digbang\Backoffice\Actions\Form as FormAction;
use Digbang\Backoffice\Inputs\Collection;
use Digbang\Backoffice\Inputs\InputInterface;
use Illuminate\Session\Store;
use Illuminate\Support\ViewErrorBag;

class Form
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @var Store
     */
    protected $session;

    /**
     * @var Collection|InputInterface[]
     */
    protected $collection;

    /**
     * @var FormAction
     */
    protected $form;

    /**
     * @var \Digbang\Backoffice\Actions\Action
     */
    protected $cancelAction;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $submitLabel;

    public function __construct($view, FormAction $form, Collection $collection, Store $session, $cancelAction, $options = [])
    {
        $this->view = $view;
        $this->form = $form;
        $this->collection = $collection;
        $this->cancelAction = $cancelAction ?: array_get($_SERVER, 'HTTP_REFERER');
        $this->session = $session;
        $this->options = $options;

        $this->setSubmitLabel($this->form->label());
    }

    /**
     * @return Collection|InputInterface[]
     */
    public function inputs()
    {
        return $this->collection;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $errors = $this->session->get('errors') ?: new ViewErrorBag();

        return \View::make($this->view, [
            'label'        => $this->form->label(),
            'formOptions'  => $this->buildOptions($this->form->target(), $this->form->method(), $this->options),
            'inputs'       => $this->collection,
            'cancelAction' => $this->cancelAction,
            'submitLabel'  => $this->submitLabel,
            'errors'       => $errors,
        ]);
    }

    public function hasFile()
    {
        return $this->collection->hasFile();
    }

    protected function buildOptions($action, $method, $options = [])
    {
        $options['url'] = $action;
        $options['method'] = $method;
        $options['files'] = $this->hasFile();

        return array_add($options, 'class', 'form-horizontal form-bordered');
    }

    public function value($name)
    {
        if ($input = $this->collection->find($name)) {
            return $input->value();
        }
    }

    public function fill(array $values)
    {
        foreach ($values as $name => $value) {
            if ($input = $this->collection->find($name)) {
                $this->collection->setValue($name, $value);
            }
        }
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * @param string $label
     */
    public function setSubmitLabel($label)
    {
        $this->submitLabel = $label;
    }
}
