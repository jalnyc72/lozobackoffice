<?php

namespace Digbang\Backoffice\Listings;

use Countable;
use Digbang\Backoffice\Actions\Collection as ActionCollection;
use Digbang\Backoffice\Controls\ControlInterface;
use Digbang\Backoffice\Extractors\ValueExtractor;
use Digbang\Backoffice\Extractors\ValueExtractorFacade;
use Digbang\Backoffice\Inputs\FilterCollection;
use Digbang\Backoffice\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class Listing implements Renderable, Countable
{
    /**
     * @var FilterCollection
     */
    protected $filters;

    /**
     * @var ColumnCollection
     */
    protected $columns;

    /**
     * @var ActionCollection
     */
    protected $actions;

    /**
     * @var ActionCollection
     */
    protected $rowActions;

    /**
     * @var ActionCollection
     */
    protected $bulkActions;

    /**
     * @var Collection
     */
    protected $rows;

    /**
     * @var ControlInterface
     */
    protected $control;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var ValueExtractorFacade
     */
    protected $valueExtractor;

    /**
     * @var string
     */
    protected $resetAction;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int|null
     */
    protected $visibleRowActions;

    /**
     * @var array
     */
    protected $defaultSelection = [];

    /**
     * @param ControlInterface     $control
     * @param Collection           $rows
     * @param FilterCollection     $filters
     * @param ValueExtractorFacade $valueExtractor
     * @param Request|null         $request
     */
    public function __construct(ControlInterface $control, Collection $rows, FilterCollection $filters, ValueExtractorFacade $valueExtractor, Request $request = null)
    {
        $this->control = $control;
        $this->rows = $rows;
        $this->filters = $filters;
        $this->valueExtractor = $valueExtractor;
        $this->request = $request;
    }

    /**
     * @param ColumnCollection $columnCollection
     *
     * @return ColumnCollection
     */
    public function columns(ColumnCollection $columnCollection = null)
    {
        if ($columnCollection) {
            $this->columns = $columnCollection;
        }

        return $this->columns;
    }

    /**
     * @return Collection
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * @param string|null $name
     *
     * @return FilterCollection|\Digbang\Backoffice\Inputs\InputInterface|null
     */
    public function filters($name = null)
    {
        if (!$name) {
            return $this->filters;
        }

        return $this->filters->find($name);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return $this->control->render()->with([
            'columns'           => $this->columns->visible(),
            'items'             => $this->rows,
            'filters'           => $this->filters,
            'resetAction'       => $this->getResetAction(),
            'actions'           => $this->actions(),
            'rowActions'        => $this->rowActions(),
            'visibleRowActions' => $this->visibleRowActions,
            'bulkActions'       => $this->bulkActions(),
            'defaultSelection'  => $this->defaultSelection,
            'paginator'         => $this->paginator,
        ]);
    }

    /**
     * @param array|\Traversable $elements
     *
     * @return $this
     */
    public function fill($elements)
    {
        $this->addElements($elements);

        if ($elements instanceof Paginator) {
            $this->paginator = $elements;
            $this->paginator->appends($this->request->all());
        }

        return $this;
    }

    /**
     * @param ActionCollection $actions
     */
    public function setActions(ActionCollection $actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return ActionCollection
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * @param ActionCollection $rowActions
     *
     * @param int|null $visibleCount
     *  Sets the visible actions. The rest will be available inside a dropdown action with a special icons.
     *  - null will show all actions
     *  - zero will hide all actions into the dropdown
     *  The order of the actions is the order of the ActionCollection
     */
    public function setRowActions(ActionCollection $rowActions, int $visibleCount = null)
    {
        $this->visibleRowActions = $visibleCount ?? PHP_INT_MAX;
        $this->rowActions = $rowActions;
    }

    /**
     * @return ActionCollection
     */
    public function rowActions()
    {
        return $this->rowActions;
    }

    /**
     * @param ActionCollection $actions
     * @param array $defaultSelection   set of row identifiers that must be pre-checked on render.
     */
    public function setBulkActions(ActionCollection $actions, array $defaultSelection = [])
    {
        $this->bulkActions = $actions;
        $this->defaultSelection = $defaultSelection;
    }

    /**
     * @return ActionCollection
     */
    public function bulkActions()
    {
        return $this->bulkActions;
    }

    /**
     * @param string $url
     */
    public function setResetAction($url)
    {
        $this->resetAction = $url;
    }

    /**
     * @return null|string
     */
    public function getResetAction()
    {
        return $this->resetAction ?: (isset($this->request) ? $this->request->url() : null);
    }

    /**
     * Count elements of an object.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             The return value is cast to an integer.
     */
    public function count()
    {
        return $this->rows->count();
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->control = $this->control->changeView($view);
    }

    /**
     * Add a specific ValueExtractor to a column.
     *
     * @param string                  $column
     * @param callable|ValueExtractor $extractor
     *
     * @return $this
     */
    public function addValueExtractor($column, $extractor)
    {
        $this->valueExtractor->add($column, $extractor);

        return $this;
    }

    /**
     * Set a global ValueExtractor for all configured columns.
     * This will override previously configured ValueExtractors.
     *
     * @param callable|ValueExtractor $extractor
     */
    public function setGlobalValueExtractor($extractor)
    {
        $this->columns->map(function (Column $column) use ($extractor) {
            $this->valueExtractor->add($column->getId(), $extractor);
        });
    }

    /**
     * @param array|Collection $elements
     */
    protected function addElements($elements)
    {
        foreach ($elements as $element) {
            $this->rows->push($this->makeRow($element));
        }
    }

    /**
     * @param mixed $element
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function makeRow($element)
    {
        $row = [];

        foreach ($this->columns as $column) {
            /* @var $column \Digbang\Backoffice\Listings\Column */
            $row[$column->getId()] = $this->valueExtractor->extract($element, $column->getId());
        }

        return $row;
    }
}
