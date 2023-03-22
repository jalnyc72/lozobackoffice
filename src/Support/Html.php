<?php

namespace Digbang\Backoffice\Support;

final class Html
{
    /**
     * Build an HTML attribute string from an array.
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function attributes($attributes)
    {
        $html = [];

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        foreach ((array) $attributes as $key => $value) {
            $element = self::attributeElement($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected static function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            $key = $value;
        }

        if (!is_null($value)) {
            return $key . '="' . e($value) . '"';
        }
    }
}
