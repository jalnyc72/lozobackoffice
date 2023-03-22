<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Closure;
use Digbang\Backoffice\Support\EvaluatorTrait;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 * @method $this addDataToggle($message)
 * @method $this addDataPlacement($message)
 * @method $this addTitle($message)
 */
class ActionBuilder implements ActionBuilderInterface
{
    use EvaluatorTrait;

    /**
     * @var ActionFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $target;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string
     */
    private $view;

    /**
     * @var string
     */
    private $icon;

    /**
     * @param ActionFactory $factory
     */
    public function __construct(ActionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function to($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function labeled($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function view($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function icon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add($attribute, $value)
    {
        if (isset($this->options[$attribute])) {
            $value = $this->getConcatenatedValue($this->options[$attribute], $value);
        }

        $this->options[$attribute] = $value;

        return $this;
    }

    /**
     * @param string|callable $previous
     * @param string|callable $value
     *
     * @return Closure|string
     */
    protected function getConcatenatedValue($previous, $value)
    {
        if ($previous instanceof Closure || $value instanceof Closure) {
            return function (LaravelCollection $row) use ($previous, $value) {
                return $this->evaluate($previous, $row) . ' ' . $this->evaluate($value, $row);
            };
        }

        return "$previous $value";
    }

    /**
     * {@inheritdoc}
     */
    public function asLink()
    {
        return $this->factory->link(
            $this->target,
            $this->label,
            $this->options,
            $this->view,
            $this->icon
        );
    }

    /**
     * {@inheritdoc}
     */
    public function asForm($method = 'POST')
    {
        return $this->factory->form(
            $this->target,
            $this->label,
            $method,
            $this->options,
            $this->view
        );
    }

    /**
     * @param string $func
     * @param array  $args
     *
     * @throws \BadMethodCallException
     *
     * @return ActionBuilderInterface
     */
    public function __call($func, $args)
    {
        if (mb_strpos($func, 'add') === 0) {
            $addWhat = snake_case(mb_substr($func, 3), '-');

            return $this->add($addWhat, array_shift($args));
        }

        throw new \BadMethodCallException("Method $func does not exist.");
    }
}
