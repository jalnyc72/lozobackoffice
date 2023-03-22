<?php

namespace Digbang\Backoffice\Inputs;

use Illuminate\Contracts\View\Factory;

/**
 * @method Translatable text(string $name, string $label = null, $options = [])
 * @method Translatable dropdown(string $name, string $label = null, $data = [], $options = [])
 * @method Translatable textarea(string $name, string $label = null, $options = [])
 * @method Translatable wysiwyg(string $name, string $label = null, $options = [])
 * @method Translatable literal(string $name, $content, $options = [])
 */
class TranslatableInputFactory
{
    /**
     * @var InputFactory
     */
    protected $inputFactory;

    /**
     * @var string[]
     */
    private $languages;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @param InputFactory $inputFactory
     * @param Factory      $viewFactory
     * @param string[]     $languages
     */
    public function __construct(InputFactory $inputFactory, Factory $viewFactory, array $languages)
    {
        $this->inputFactory = $inputFactory;
        $this->viewFactory = $viewFactory;
        $this->languages = $languages;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws \BadMethodCallException
     *
     * @return Translatable
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->inputFactory, $name)) {
            return new Translatable(
                $this->viewFactory,
                $this->inputFactory->$name(...$arguments),
                $this->languages
            );
        }

        throw new \BadMethodCallException("Method [$name] does not exist on this object.");
    }
}
