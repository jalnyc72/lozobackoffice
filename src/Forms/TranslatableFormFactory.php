<?php

namespace Digbang\Backoffice\Forms;

use Illuminate\Contracts\View\Factory;

class TranslatableFormFactory
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var array
     */
    private $languages;

    /**
     * @param FormFactory $formFactory
     * @param Factory     $viewFactory
     * @param string[]    $languages
     */
    public function __construct(FormFactory $formFactory, Factory $viewFactory, array $languages)
    {
        $this->formFactory = $formFactory;
        $this->viewFactory = $viewFactory;
        $this->languages = $languages;
    }

    /**
     * @param string|\Closure $target
     * @param string|\Closure $label
     * @param string          $method
     * @param string          $cancelAction
     * @param array           $options
     *
     * @return TranslatableForm|Form
     */
    public function form($target, $label, $method = 'POST',  $cancelAction = '', $options = [])
    {
        $form = $this->formFactory->make($target, $label, $method, $cancelAction, $options);

        return new TranslatableForm($this->viewFactory, $form, $this->languages);
    }
}
