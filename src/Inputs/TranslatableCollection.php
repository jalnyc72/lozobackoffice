<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Inputs;

use Countable;
use IteratorAggregate;

/**
 * @method TranslatableCollection text(string $name, string $label = null, $options = [])
 * @method TranslatableCollection dropdown(string $name, string $label = null, $data = [], $options = [])
 * @method TranslatableCollection textarea(string $name, string $label = null, $options = [])
 * @method TranslatableCollection wysiwyg(string $name, string $label = null, $options = [])
 * @method TranslatableCollection literal(string $name, $content, $options = [])
 */
class TranslatableCollection implements Countable, IteratorAggregate
{
    /**
     * @var TranslatableInputFactory
     */
    private $factory;

    /**
     * @var Collection
     */
    private $inputs;

    public function __construct(TranslatableInputFactory $factory, Collection $inputs)
    {
        $this->factory = $factory;
        $this->inputs = $inputs;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return TranslatableCollection
     */
    public function __call($name, $arguments)
    {
        $this->inputs->add($this->factory->$name(...$arguments));

        return $this;
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
