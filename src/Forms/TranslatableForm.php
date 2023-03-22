<?php

namespace Digbang\Backoffice\Forms;

use Digbang\Backoffice\Support\HasTranslations;
use Illuminate\Contracts\View\Factory;

/**
 * @mixin Form
 */
class TranslatableForm
{
    use HasTranslations;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var string
     */
    private $wrappingView = 'backoffice::forms.translatable';

    /**
     * @var string[]
     */
    private $except = [];

    /**
     * @param Factory  $viewFactory
     * @param Form     $form
     * @param string[] $languages
     */
    public function __construct(Factory $viewFactory, Form $form, array $languages)
    {
        $this->viewFactory = $viewFactory;
        $this->form = $form;
        $this->languages = $this->parseLanguages($languages);
    }

    public function changeWrappingView($view)
    {
        $this->wrappingView = $view;
    }

    /**
     * Exclude inputs from getting translated.
     *
     * @param array|string $name
     * @param string       $_
     *
     * @return $this
     */
    public function except($name)
    {
        $name = is_array($name) ? $name : func_get_args();

        $this->except = array_merge($this->except, $name);

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->form->setView($this->wrappingView);

        return $this->form->render()->with([
            'languages' => $this->languages,
            'form'      => $this->form,
            'except'    => $this->except,
        ]);
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->form, $name)) {
            return $this->form->$name(...$arguments);
        }

        throw new \BadMethodCallException("Method [$name] does not exist on this object.");
    }
}
