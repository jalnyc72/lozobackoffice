<?php

namespace Digbang\Backoffice\Support;

trait HasTranslations
{
    /**
     * @var string[]
     */
    protected $languages;

    /**
     * @param string $language
     *
     * @return bool
     */
    protected function hasLanguage($language)
    {
        return $language === null || array_key_exists($language, $this->languages);
    }

    /**
     * Parses the languages array. Turns numeric indexes into key => label format.
     *
     * @param array $languages
     *
     * @return array
     */
    protected function parseLanguages(array $languages)
    {
        $parsed = [];

        foreach ($languages as $code => $label) {
            if (is_integer($code)) {
                $code = $label;
            }

            $parsed[$code] = $label;
        }

        return $parsed;
    }
}
