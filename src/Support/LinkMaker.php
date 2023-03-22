<?php

namespace Digbang\Backoffice\Support;

use Digbang\Backoffice\Listings\Column;
use Digbang\Fonts\FontManager;
use Illuminate\Http\Request;

class LinkMaker
{
    const SORT_BY = 'sort_by';
    const SORT_SENSE = 'sort_sense';
    const SORT_SENSE_ASC = 'asc';
    const SORT_SENSE_DESC = 'desc';

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var FontManager
     */
    protected $fontManager;

    /**
     * @param Request     $request
     * @param FontManager $fontManager
     */
    public function __construct(Request $request, FontManager $fontManager)
    {
        $this->request = $request;
        $this->fontManager = $fontManager;
    }

    /**
     * Gets the current link to sort the column.
     *
     * @param Column $column
     *
     * @return string
     */
    public function sort(Column $column)
    {
        $parameters = $this->getParameters();
        $by = $column->getId();
        $sense = self::SORT_SENSE_ASC;

        $isSortedBy = $this->isCurrentColumnSorted($column, $parameters);

        if ($isSortedBy && array_get($parameters, self::SORT_SENSE) == $sense) {
            $sense = self::SORT_SENSE_DESC;
        }

        return $this->makeHtmlLink($column, $by, $sense, $isSortedBy);
    }

    /**
     * @param Column $column
     * @param string $by
     * @param string $sense
     * @param bool   $isSortedBy
     *
     * @return string
     */
    protected function makeHtmlLink(Column $column, $by, $sense, $isSortedBy)
    {
        $href = $this->to($by, $sense);
        $body = $column->getLabel();
        $icon = 'sort';

        if ($isSortedBy) {
            $body = "<strong>$body</strong>";
            $icon = "sort-$sense";
        }

        $body .= '&nbsp;' . $this->fontManager->icon($icon);

        return "<a href=\"$href\" class=\"sort-link\">$body</a>";
    }

    /**
     * @param string $by
     * @param string $sense
     *
     * @return string
     */
    protected function to($by, $sense = self::SORT_SENSE_ASC)
    {
        return $this->request->url() . '?' .
            http_build_query(array_merge(
                $this->request->except(['page']),
                [
                    self::SORT_BY    => $by,
                    self::SORT_SENSE => $this->parseSortSense($sense),
                ]
            ), null, '&');
    }

    /**
     * @param string $sense
     *
     * @return string
     */
    protected function parseSortSense($sense)
    {
        return $sense == self::SORT_SENSE_DESC ?
            // its desc!
            self::SORT_SENSE_DESC :
            // its not desc, return asc.
            self::SORT_SENSE_ASC;
    }

    /**
     * @return array
     */
    protected function getParameters()
    {
        return $this->request->only([
            self::SORT_BY, self::SORT_SENSE,
        ]);
    }

    /**
     * @param Column $column
     * @param array  $parameters
     *
     * @return bool
     */
    protected function isCurrentColumnSorted(Column $column, array $parameters)
    {
        return array_get($parameters, self::SORT_BY) == $column->getId();
    }
}
