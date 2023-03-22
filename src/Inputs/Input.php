<?php
namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;
use Digbang\Backoffice\Controls\ControlWrapperTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\App;

class Input implements InputInterface
{
    use ControlWrapperTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $defaultsTo;

    /**
     * @var bool
     */
    protected $readonly = false;

    /**
     * @var bool
     */
    protected $visible = true;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var array
     */
    protected $languages = [];

    /**
     * @param ControlInterface $control
     * @param string           $name
     * @param mixed            $value
     */
    public function __construct(ControlInterface $control, $name, $value = null)
    {
        $this->setName($name);
        $this->setValue($name, $value);

        $this->control = $control;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function changeName($name)
    {
        return new static($this->control, $name, $this->value);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function dottedName()
    {
        return trim(str_replace(['[', ']'], ['.', ''], $this->name), '.');
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setValue($name, $value)
    {
        if ($this->hasName($name)) {
            $this->value = $value;
        }
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    public function defaultsTo($value)
    {
        $this->defaultsTo = $value;
    }

    public function setReadonly()
    {
        $this->readonly = true;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        if (!$this->control->option('id')) {
            $this->control = $this->control->changeOption('id', $this->name());
        }

        return $this->control->render()
            ->with([
                'name'     => $this->name(),
                'value'    => $this->value() !== null ? $this->value() : $this->defaultsTo,
                'readonly' => $this->readonly,
            ]);
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
        return $this->name == $name;
    }

    public function hide()
    {
        $this->visible = false;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Sets the input as required, will then add a classname to the form-group.
     * {@inheritdoc}
     */
    public function setRequired(bool $required = true): void
    {
        $this->required = $required;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param string[] $languages
     *
     * @return Translatable
     */
    public function translate(array $languages)
    {
        // SUPER HACK: injecting the view factory here to avoid touching every other place for dependencies.
        //             Should be refactored.
        return new Translatable(App::make(Factory::class), $this, $languages);
    }

    /**
     * {@inheritdoc}
     */
    public function isFile(): bool
    {
        return false;
    }
}
