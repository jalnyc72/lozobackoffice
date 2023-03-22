<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Inputs;

interface InputFactoryInterface
{
    /**
     * @param string      $name
     * @param string|null $label
     * @param array       $options
     *
     * @return InputInterface
     */
    public function text($name, $label = null, $options = []);

    /**
     * @param string      $name
     * @param string|null $label
     * @param array       $data
     * @param array       $options
     *
     * @return DropDown
     */
    public function dropdown($name, $label = null, $data = [], $options = []);

    /**
     * @param string      $name
     * @param string|null $label
     * @param string      $route
     * @param array       $options
     *
     * @return Suggest
     */
    public function suggest($name, $label = null, $route, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function button($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function checkbox($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function integer($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function date($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function datetime($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function time($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function password($name, $label, $options = []);

    /**
     * @param string      $name
     * @param string|null $label
     * @param array       $options
     *
     * @return InputInterface
     */
    public function textarea($name, $label = null, $options = []);

    /**
     * @param string      $name
     * @param string|null $label
     * @param array       $options
     *
     * @return InputInterface
     */
    public function wysiwyg($name, $label = null, $options = []);

    /**
     * @param string     $name
     * @param Collection $collection
     * @param string     $label
     * @param array      $options
     *
     * @return Composite
     */
    public function composite($name, Collection $collection, $label = '', $options = []);

    /**
     * @param string $name
     * @param array  $options
     *
     * @return InputInterface
     */
    public function hidden($name, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function colorPicker($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return InputInterface
     */
    public function boolean($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $label
     * @param array  $options
     *
     * @return File
     */
    public function file($name, $label, $options = []);

    /**
     * @param string $name
     * @param string $content
     * @param array  $options
     *
     * @return InputInterface
     */
    public function literal($name, $content, $options = []);

    /**
     * @param string $name
     * @param string|null $label
     * @param array $data
     * @param array $options
     *
     * @return InputInterface
     */
    public function tagger($name, $label = null, $data = [], $options = []);

    /**
     * @param string $label
     * @param string $view
     * @param array $with
     *
     * @return InputInterface
     */
    public function view($label, $view, $with = []);

    /**
     * @param string[] $languages
     *
     * @return TranslatableInputFactory
     */
    public function translatable(array $languages);

    /**
     * @return Collection
     */
    public function collection();
}
