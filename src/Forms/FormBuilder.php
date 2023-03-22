<?php

namespace Digbang\Backoffice\Forms;

use Collective\Html\FormBuilder as CollectiveFormBuilder;

class FormBuilder extends CollectiveFormBuilder
{
    /**
     * Create a select box field.
     *
     * @param  string $name
     * @param  array  $list
     * @param  string $selected
     * @param  array  $selectAttributes
     * @param  array  $optionsAttributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function select(
        $name,
        $list = [],
        $selected = null,
        $selectAttributes = [],
        $optionsAttributes = []
    )
    {
        // When building a select box the "value" attribute is really the selected one
        // so we will use that when checking the model or session for a value which
        // should provide a convenient method of re-populating the forms on post.
        $selected = $this->getValueAttribute($name, $selected);

        $selectAttributes['id'] = $this->getIdAttribute($name, $selectAttributes);

        if (! isset($selectAttributes['name'])) {
            $selectAttributes['name'] = $name;
        }

        // We will simply loop through the options and build an HTML value for each of
        // them until we have an array of HTML declarations. Then we will join them
        // all together into one single HTML element that can be put on the form.
        $html = [];

        if (isset($selectAttributes['placeholder'])) {
            $html[] = $this->placeholderOption($selectAttributes['placeholder'], $selected);
            unset($selectAttributes['placeholder']);
        }

        foreach ($list as $value => $display) {
            $optionAttributes = isset($optionsAttributes[$value]) ? $optionsAttributes[$value] : [];

            $html[] = $this->getSelectOption($display, $value, $selected, $optionAttributes);
        }

        // Once we have all of this HTML, we can join this into a single element after
        // formatting the attributes into an HTML "attributes" string, then we will
        // build out a final select statement, which will contain all the values.
        $selectAttributes = $this->html->attributes($selectAttributes);

        $list = implode('', $html);

        return $this->toHtmlString("<select{$selectAttributes}>{$list}</select>");
    }

    /**
     * Get the select option for the given value.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function getSelectOption($display, $value, $selected, array $attributes = [])
    {
        if (is_array($display)) {
            return $this->optionGroup($display, $value, $selected, $attributes);
        }
        return $this->option($display, $value, $selected, $attributes);
    }

    /**
     * Create an option group form element.
     *
     * @param  array  $list
     * @param  string $label
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function optionGroup($list, $label, $selected, array $attributes = [])
    {
        $html = [];

        foreach ($list as $value => $display) {
            $html[] = $this->option($display, $value, $selected, $attributes);
        }

        return $this->toHtmlString('<optgroup label="' . e($label) . '">' . implode('', $html) . '</optgroup>');
    }

    /**
     * Create a select element option.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function option($display, $value, $selected, array $attributes = [])
    {
        $selected = $this->getSelectedValue($value, $selected);

        $options = ['value' => $value, 'selected' => $selected] + $attributes;

        return $this->toHtmlString('<option' . $this->html->attributes($options) . '>' . e($display) . '</option>');
    }
}
