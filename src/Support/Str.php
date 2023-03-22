<?php

namespace Digbang\Backoffice\Support;

use Illuminate\Support\Str as IlluminateStr;
use Illuminate\Translation\Translator;

/**
 * Class Str.
 */
class Str
{
    protected $str;
    protected $lang;

    public function __construct(IlluminateStr $str, Translator $lang)
    {
        $this->str = $str;
        $this->lang = $lang;
    }

    public function titleFromSlug($slug)
    {
        return $this->str->title(str_replace(['-', '_'], ' ', $slug));
    }

    public function parse($value)
    {
        if (is_bool($value)) {
            return $value ? $this->lang->get('backoffice::default.yes') : $this->lang->get('backoffice::default.no');
        }

        return value($value);
    }
}
