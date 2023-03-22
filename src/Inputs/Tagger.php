<?php

namespace Digbang\Backoffice\Inputs;

class Tagger extends DropDown
{
    /**
     * @return array
     */
    private function transformData()
    {
        return collect($this->data)->map(function($label, $id) {
            return ['id' => $id, 'text' => $label];
        })->values()->all();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $this->changeOption('data-tagger', true);
        $this->changeOption('data-options', json_encode($this->transformData()));

        return parent::render();
    }
}
