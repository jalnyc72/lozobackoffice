<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Contracts\View\Factory;

class FilterInputFactory implements InputFactoryInterface
{
    /**
     * @var InputFactory
     */
    protected $inputFactory;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @param InputFactory $inputFactory
     * @param Factory      $viewFactory
     */
    public function __construct(InputFactory $inputFactory, Factory $viewFactory)
    {
        $this->inputFactory = $inputFactory;
        $this->viewFactory = $viewFactory;
    }

    public function text($name, $label = null, $options = [])
    {
        return $this->createFilter($this->inputFactory->text($name, $label, $options));
    }

    public function dropdown($name, $label = null, $data = [], $options = [])
    {
        return $this->createFilter($this->inputFactory->dropdown($name, $label, $data, $options));
    }

    public function suggest($name, $label = null, $route, $options = [])
    {
        return $this->createFilter($this->inputFactory->suggest($name, $label, $route, $options));
    }

    public function button($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->button($name, $label, $options));
    }

    public function checkbox($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->checkbox($name, $label, $options));
    }

    public function integer($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->integer($name, $label, $options));
    }

    public function date($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->date($name, $label, $options));
    }

    public function datetime($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->datetime($name, $label, $options));
    }

    public function time($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->time($name, $label, $options));
    }

    public function password($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->password($name, $label, $options));
    }

    public function textarea($name, $label = null, $options = [])
    {
        return $this->createFilter($this->inputFactory->textarea($name, $label = null, $options));
    }

    public function wysiwyg($name, $label = null, $options = [])
    {
        return $this->createFilter($this->inputFactory->wysiwyg($name, $label = null, $options));
    }

    public function composite($name, Collection $collection, $label = '', $options = [])
    {
        return $this->createFilter($this->inputFactory->composite($name, $collection, $label, $options));
    }

    public function hidden($name, $options = [])
    {
        return $this->createFilter($this->inputFactory->hidden($name, $options));
    }

    public function colorPicker($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->colorPicker($name, $label, $options));
    }

    public function boolean($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->boolean($name, $label, $options));
    }

    public function file($name, $label, $options = [])
    {
        return $this->createFilter($this->inputFactory->file($name, $label, $options));
    }

    public function literal($name, $content, $options = [])
    {
        return $this->createFilter($this->inputFactory->literal($name, $content, $options));
    }

    public function tagger($name, $label = null, $data = [], $options = [])
    {
        return $this->createFilter($this->inputFactory->tagger($name, $label, $data, $options));
    }

    public function view($label, $view, $with = [])
    {
        return $this->createFilter($this->inputFactory->view($label, $view, $with));
    }

    public function translatable(array $languages)
    {
        return new TranslatableInputFactory($this->inputFactory, $this->viewFactory, $languages);
    }

    public function collection()
    {
        return new FilterCollection($this, new Collection($this->inputFactory, new DigbangCollection()));
    }

    /**
     * @param InputInterface $input
     * @return Filter
     */
    public function createFilter(InputInterface $input)
    {
        return new Filter($this->viewFactory, $input);
    }
}
