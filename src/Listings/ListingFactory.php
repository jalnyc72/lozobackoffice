<?php

namespace Digbang\Backoffice\Listings;

use Digbang\Backoffice\Controls\ControlFactory;
use Digbang\Backoffice\Extractors\ValueExtractorFacade;
use Digbang\Backoffice\Inputs\FilterInputFactory;
use Digbang\Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Http\Request;

class ListingFactory
{
    /**
     * @var FilterInputFactory
     */
    protected $inputFactory;

    /**
     * @var ControlFactory
     */
    protected $controlFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param FilterInputFactory $inputFactory
     * @param ControlFactory $controlFactory
     * @param Request        $request
     */
    public function __construct(FilterInputFactory $inputFactory, ControlFactory $controlFactory, Request $request = null)
    {
        $this->inputFactory = $inputFactory;
        $this->controlFactory = $controlFactory;
        $this->request = $request;
    }

    /**
     * @param ColumnCollection $columns
     *
     * @return Listing
     */
    public function make(ColumnCollection $columns)
    {
        $listing = new Listing(
            $this->controlFactory->make('backoffice::listing', ''),
            new DigbangCollection(),
            $this->inputFactory->collection(),
            new ValueExtractorFacade(),
            $this->request
        );

        $listing->columns($columns);

        return $listing;
    }
}
