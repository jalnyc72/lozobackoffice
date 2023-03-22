<?php

namespace Digbang\Backoffice\Listings;

use Digbang\Backoffice\Support\Collection;

class ColumnCollection extends Collection
{
    /**
     * @param array $labels
     */
    public function __construct(array $labels = [])
    {
        parent::__construct($this->createColumns($labels));
    }

    /**
     * @param string[]|string $ids
     *
     * @return $this
     */
    public function hide($ids)
    {
        return $this->toggleHidden($ids, true);
    }

    /**
     * @param string[]|string $ids
     *
     * @return $this
     */
    public function show($ids)
    {
        return $this->toggleHidden($ids, false);
    }

    /**
     * @param string[]|string $ids
     * @param bool            $hide
     *
     * @return $this
     */
    protected function toggleHidden($ids, $hide)
    {
        $this->only($ids)->each(function (Column $column) use ($hide) {
            $column->setHidden($hide);
        });

        return $this;
    }

    /**
     * @param string[]|string $ids
     *
     * @return $this
     */
    public function sortable($ids)
    {
        $this->only($ids)->each(function (Column $column) {
            $column->setSortable(true);
        });

        $this->except($ids)->each(function (Column $column) {
            $column->setSortable(false);
        });

        return $this;
    }

    /**
     * @return ColumnCollection
     */
    public function visible()
    {
        return $this->filter(function (Column $column) {
            return $column->isHidden() === false;
        });
    }

    /**
     * @return ColumnCollection
     */
    public function hidden()
    {
        return $this->filter(function (Column $column) {
            return $column->isHidden() === true;
        });
    }

    /**
     * @param array $labels
     *
     * @return Column[]
     */
    protected function createColumns(array $labels)
    {
        return (new Collection($labels))
            ->map([$this, 'createColumn'])
            ->reduce(function (array $columns, Column $column) {
                $columns[$column->getId()] = $column;

                return $columns;
            }, []);
    }

    /**
     * @param string   $id
     * @param callable $accessor
     *
     * @return $this
     */
    public function setAccessor($id, $accessor)
    {
        $this->get($id)->setAccessor($accessor);

        return $this;
    }

    /**
     * @param string|Column $label
     * @param string|int    $id
     *
     * @return Column
     */
    public function createColumn($label, $id = null)
    {
        if ($label instanceof Column) {
            return $label;
        }

        if (!is_string($id)) {
            $id = $label;
        }

        return new Column($id, $label);
    }

    /**
     * Do not allow pushing items without id.
     *
     * {@inheritdoc}
     */
    public function push($value)
    {
        $column = $this->createColumn($value);

        $this->offsetSet($column->getId(), $column);

        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * This method will not return anything other than Column objects.
     *
     * @param string      $key
     * @param Column|null $default Default only works if a Column object is given.
     *
     * @throws \OutOfBoundsException
     *
     * @return Column
     */
    public function get($key, $default = null)
    {
        $column = parent::get($key, $default);

        if (!$column instanceof Column) {
            throw new \OutOfBoundsException("Column [$key] not found.");
        }

        return $column;
    }
}
