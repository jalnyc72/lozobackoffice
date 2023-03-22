<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Inputs;

use Countable;
use IteratorAggregate;

/**
 * @method Filter text($name, $label = null, $options = [])
 * @method Filter dropdown($name, $label = null, $data = [], $options = [])
 * @method Filter suggest($name, $label = null, $route, $options = [])
 * @method Filter button($name, $label, $options = [])
 * @method Filter checkbox($name, $label, $options = [])
 * @method Filter integer($name, $label, $options = [])
 * @method Filter date($name, $label, $options = [])
 * @method Filter datetime($name, $label, $options = [])
 * @method Filter time($name, $label, $options = [])
 * @method Filter password($name, $label, $options = [])
 * @method Filter textarea($name, $label = null, $options = [])
 * @method Filter wysiwyg($name, $label = null, $options = [])
 * @method Filter composite($name, Collection $collection, $label = '', $options = [])
 * @method Filter hidden($name, $options = [])
 * @method Filter colorPicker($name, $label, $options = [])
 * @method Filter boolean($name, $label, $options = [])
 * @method Filter file($name, $label, $options = [])
 * @method Filter literal($name, $content = '', $options = [])
 * @method Filter tagger($name, $label = null, $data = [], $options = [])
 * @method Filter view($label, $view, $with = [])
 * @method TranslatableCollection translatable(array $languages)
 * @method InputFactoryInterface collection()
 */
class FilterCollection implements Countable, IteratorAggregate
{
    /**
     * @var FilterInputFactory
     */
    private $factory;

    /**
     * @var Collection
     */
    private $inputs;

    public function __construct(FilterInputFactory $factory, Collection $inputs)
    {
        $this->factory = $factory;
        $this->inputs = $inputs;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return Filter|InputInterface
     */
    public function __call($name, $arguments)
    {
        return $this->inputs->add($this->factory->$name(...$arguments));
    }

    /**
     * @param string $name
     *
     * @return InputInterface|null
     */
    public function find($name)
    {
        return $this->inputs->find($name);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setValue($name, $value)
    {
        $this->inputs->setValue($name, $value);
    }

    /**
     * @param Filter $input
     *
     * @return Filter
     */
    public function add(Filter $input)
    {
        return $this->inputs->add($input);
    }

    /**
     * @return Filter[]|Collection
     */
    public function all()
    {
        return $this->inputs;
    }

    /**
     * @return Filter[]|Collection
     */
    public function getVisible()
    {
        return $this->inputs->getVisible();
    }

    /**
     * @return Filter[]|Collection
     */
    public function getHidden()
    {
        return $this->inputs->getHidden();
    }

    /**
     * Returns true if at least one item on the collection is a File input.
     *
     * @return bool
     */
    public function hasFile(): bool
    {
        return $this->inputs->hasFile();
    }

    /**
     * @param Filter $filter
     * @return bool
     */
    public function shouldBeVisible(Filter $filter)
    {
        return !$this->hasAnyValue() && ($filter->isVisible() || $this->shouldApplyBackwardsCompatibility());
    }

    /**
     * @return bool
     */
    private function hasAnyValue()
    {
        return $this->inputs->all()->filter(function(Filter $item) { return !is_null($item->value()); })->count() > 0;
    }

    /**
     * @return bool
     */
    private function shouldApplyBackwardsCompatibility()
    {
        return !$this->hasAtLeastSomeVisible(2);
    }

    /**
     * @param int $quantity
     * @return bool
     */
    private function hasAtLeastSomeVisible(int $quantity)
    {
        return $this->inputs->all()->filter(function(Filter $item) { return $item->isVisible(); })->count() >= $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->inputs->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->inputs;
    }
}
