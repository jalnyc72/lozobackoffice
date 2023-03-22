<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Support\HasTranslations;
use Digbang\Backoffice\Support\WrapsUI;
use Illuminate\Contracts\View\Factory;

class Translatable extends InputDecorator
{
    use HasTranslations;
    use WrapsUI;

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @param Factory        $viewFactory
     * @param InputInterface $wrapped
     * @param string[]       $languages
     */
    public function __construct(Factory $viewFactory, InputInterface $wrapped, array $languages)
    {
        parent::__construct($wrapped);

        $this->viewFactory = $viewFactory;
        $this->languages = $this->parseLanguages($languages);

        $this->changeWrappingView('backoffice::inputs.translatable');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return $this->viewFactory->make($this->wrappingView, [
            'languages' => $this->languages,
            'inputs'    => $this->renameInputs(),
        ]);
    }

    /**
     * @param string $name
     *
     * @return Translatable
     */
    public function changeName($name)
    {
        return new self($this->viewFactory, $this->wrapped->changeName($name), $this->languages);
    }

    /**
     * The input value as received from the user.
     *
     * @return string[]
     */
    public function value()
    {
        return (array) $this->wrapped->value();
    }

    /**
     * Check if the input matches the given name.
     *
     * @param $name
     *
     * @return bool
     */
    public function hasName($name)
    {
        list($name, $language) = $this->parseName($name);

        return $this->wrapped->hasName($name) && $this->hasLanguage($language);
    }

    /**
     * Sets the value of the input matching the given name.
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function setValue($name, $value)
    {
        if (!$this->hasName($name)) {
            return;
        }

        list($name, $language) = $this->parseName($name);

        if ($language !== null) {
            $value = [$language => $value];
        }

        $this->wrapped->setValue($name, array_merge(
            (array) $this->wrapped->value(),
            (array) $value
        ));
    }

    /**
     * @param string[] $languages
     *
     * @return Translatable
     */
    public function translate(array $languages)
    {
        return new self($this->viewFactory, $this->wrapped, $languages);
    }

    /**
     * @param string $name
     *
     * @return string[]
     */
    private function parseName($name)
    {
        if (mb_strpos($name, '.') !== false) {
            return explode('.', $name, 2);
        }

        if (mb_strpos($name, '[') !== false) {
            list($name, $language) = explode('[', $name, 2);

            return [
                $name,
                trim($language, '[]'),
            ];
        }

        return [$name, null];
    }

    /**
     * @return InputInterface[]
     */
    protected function renameInputs()
    {
        $inputs = [];

        foreach ($this->languages as $code => $label) {
            $inputs[$code] = $this->wrapped->changeName($this->wrapped->name() . "[$code]");
        }

        return $inputs;
    }
}
