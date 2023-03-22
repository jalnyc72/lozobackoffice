<?php

namespace Digbang\Backoffice\Urls;

use Illuminate\Routing\UrlGenerator;

class PersistentUrlGeneratorExtension extends UrlGenerator
{
    /**
     * @var PersistentUrlGenerator
     */
    private $urlGenerator;

    /**
     * @param PersistentUrlGenerator $urlGenerator
     */
    public function setUrlGenerator(PersistentUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        return $this->urlGenerator->route($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function action($action, $parameters = [], $absolute = true)
    {
        return $this->urlGenerator->action($action, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function to($path, $extra = [], $secure = null)
    {
        return $this->urlGenerator->to($path, $extra, $secure);
    }
}
