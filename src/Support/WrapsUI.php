<?php

namespace Digbang\Backoffice\Support;

trait WrapsUI
{
    /**
     * @var string
     */
    protected $wrappingView;

    /**
     * @param string $view
     *
     * @return void
     */
    public function changeWrappingView($view)
    {
        $this->wrappingView = $view;
    }
}
