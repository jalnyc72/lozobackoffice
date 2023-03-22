<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Inputs;

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Collection as LaravelCollection;
use Countable;
use IteratorAggregate;

/**
 * @method InputInterface string($name, $label, $options = [])
 * @method InputInterface array($name, $label = null, $data = [], $options = [])
 * @method InputInterface simpleArray($name, $label = null, $data = [], $options = [])
 * @method InputInterface jsonArray($name, $label = null, $data = [], $options = [])
 * @method InputInterface float($name, $label, $options = [])
 * @method InputInterface decimal($name, $label, $options = [])
 * @method InputInterface smallint($name, $label, $options = [])
 * @method InputInterface bigint($name, $label, $options = [])
 * @method InputInterface datetimetz($name, $label, $options = [])
 * @method InputInterface object($name, $label, $options = [])
 * @method InputInterface blob($name, $label, $options = [])
 * @method InputInterface binary($name, $label, $options = [])
 * @method InputInterface guid($name, $label, $options = [])
 */
class Collection implements InputFactoryInterface, Countable, IteratorAggregate
{
    /**
     * @var LaravelCollection
     */
    protected $collection;

    /**
     * @var InputFactoryInterface
     */
    protected $factory;

    /**
     * @param InputFactoryInterface $factory
     * @param LaravelCollection $collection
     */
    public function __construct(InputFactoryInterface $factory, LaravelCollection $collection)
    {
        $this->factory = $factory;
        $this->collection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function text($name, $label = null, $options = [])
    {
        return $this->add($this->factory->text($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function dropdown($name, $label = null, $data = [], $options = [])
    {
        return $this->add($this->factory->dropdown($name, $label, $data, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function suggest($name, $label = null, $route, $options = [])
    {
        return $this->add($this->factory->suggest($name, $label, $route, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function button($name, $label, $options = [])
    {
        return $this->add($this->factory->button($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($name, $label, $options = [])
    {
        return $this->add($this->factory->checkbox($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function integer($name, $label, $options = [])
    {
        return $this->add($this->factory->integer($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function date($name, $label, $options = [])
    {
        return $this->add($this->factory->date($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function datetime($name, $label, $options = [])
    {
        return $this->add($this->factory->datetime($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function time($name, $label, $options = [])
    {
        return $this->add($this->factory->time($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function password($name, $label, $options = [])
    {
        return $this->add($this->factory->password($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function textarea($name, $label = null, $options = [])
    {
        return $this->add($this->factory->textarea($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function wysiwyg($name, $label = null, $options = [])
    {
        return $this->add($this->factory->wysiwyg($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function composite($name, Collection $collection, $label = '', $options = [])
    {
        return $this->add($this->factory->composite($name, $collection, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function hidden($name, $options = [])
    {
        return $this->add($this->factory->hidden($name, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function colorPicker($name, $label, $options = [])
    {
        return $this->add($this->factory->colorPicker($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function boolean($name, $label, $options = [])
    {
        return $this->add($this->factory->boolean($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function file($name, $label, $options = [])
    {
        return $this->add($this->factory->file($name, $label, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function literal($name, $content = '', $options = [])
    {
        return $this->add($this->factory->literal($name, $content, $options));
    }

    /**
     * @inheritDoc
     */
    public function tagger($name, $label = null, $data = [], $options = [])
    {
        return $this->add($this->factory->tagger($name, $label, $data, $options));
    }

    /**
     * @inheritDoc
     */
    public function view($label, $view, $with = [])
    {
        return $this->add($this->factory->view($label, $view, $with));
    }

    /**
     * {@inheritdoc}
     */
    public function translatable(array $languages)
    {
        return new TranslatableCollection($this->factory->translatable($languages), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function collection()
    {
        return $this->factory->collection();
    }

    /**
     * @param string $name
     *
     * @return InputInterface|null
     */
    public function find($name)
    {
        foreach ($this->collection as $input) {
            if ($input->hasName($name)) {
                return $input;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setValue($name, $value)
    {
        if ($input = $this->find($name)) {
            $input->setValue($name, $value);
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return InputInterface
     */
    public function add(InputInterface $input)
    {
        $this->collection->push($input);

        return $input;
    }

    /**
     * @return InputInterface[]|LaravelCollection
     */
    public function all()
    {
        return $this->collection;
    }

    /**
     * @return InputInterface[]|LaravelCollection
     */
    public function getVisible()
    {
        return $this->getByVisibility(true);
    }

    /**
     * @param bool $visible
     *
     *@return InputInterface[]|LaravelCollection
     */
    protected function getByVisibility($visible = true)
    {
        return $this->collection->filter(function (InputInterface $input) use ($visible) {
            return !($visible xor $input->isVisible());
        });
    }

    /**
     * @return InputInterface[]|LaravelCollection
     */
    public function getHidden()
    {
        return $this->getByVisibility(false);
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @throws \BadMethodCallException
     *
     * @return InputInterface
     */
    public function __call($name, $args)
    {
        if (Type::hasType($name)) {
            // Supported Doctrine type
            switch ($name) {
                case Type::TARRAY:
                case Type::SIMPLE_ARRAY:
                case 'simpleArray':
                case Type::JSON_ARRAY:
                case 'jsonArray':
                    $func = 'dropdown';
                    break;
                case Type::BIGINT:
                case Type::SMALLINT:
                case Type::FLOAT:
                case Type::DECIMAL:
                    $func = 'integer';
                    break;
                case Type::DATE:
                    $func = 'date';
                    break;
                case Type::DATETIME:
                case Type::DATETIMETZ:
                    $func = 'datetime';
                    break;
                case Type::TIME:
                    $func = 'time';
                    break;
                case Type::STRING:
                case Type::OBJECT:
                case Type::BLOB:
                case Type::GUID:
                default:
                    $func = 'text';
            }

            return $this->add(call_user_func_array([$this->factory, $func], $args));
        }

        if (method_exists($this->collection, $name)) {
            return call_user_func_array([$this->collection, $name], $args);
        }

        throw new \BadMethodCallException("Method $name not found.");
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * Returns true if at least one item on the collection is a File input.
     *
     * @return bool
     */
    public function hasFile(): bool
    {
        return $this->collection->contains(function (InputInterface $input) {
            return $input->isFile();
        });
    }
}
