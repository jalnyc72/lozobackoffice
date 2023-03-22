<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlInterface;

/**
 * Class Suggest.
 */
class Suggest extends Input implements InputInterface
{
    /**
     * @var string
     */
    protected $route;
    /**
     * @var string
     */
    private $data;
    /**
     * @var int
     */
    private $minimumInputLength = 3;

    public function __construct(ControlInterface $control, $name, $value = null, $route, $data = null)
    {
        parent::__construct($control, $name, $value);

        $this->setRoute($route);
        $this->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function changeName($name)
    {
        $dropDown = parent::changeName($name);
        $dropDown->route = $this->route;

        return $dropDown;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function route()
    {
        return $this->route;
    }

    public function setValue($name, $value)
    {
        if (is_array($value)) {
            $data = $ids = [];

            foreach ($value as $id => $text) {
                $data[] = ['id' => $id, 'text' => $text];
                $ids[] = $id;
            }

            if ($this->option('multiple') != 'multiple') {
                $ids = [array_shift($ids)];
                $data = array_shift($data);
            }

            $value = implode(',', $ids);

            $this->setData(json_encode($data));
        }

        parent::setValue($name, $value);
    }

    /**
     * @return string
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function minimumInputLength(): int
    {
        return $this->minimumInputLength;
    }

    /**
     * @param int $minimumInputLength
     */
    public function setMinimumInputLength(int $minimumInputLength)
    {
        $this->minimumInputLength = $minimumInputLength;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $this->buildOptions();

        return parent::render()->with([
            'data' => $this->data()
        ]);
    }

    private function buildOptions()
    {
        $options = $this->options();

        $options['data-suggest'] = true;
        $options['data-url'] = $this->route();
        $options['data-sortable'] = $options['sortable'] ?? false;
        $options['data-minimum-input-length'] = $this->minimumInputLength();

        $options['data-placeholder'] =
            $options['data-placeholder'] ??
            trans('backoffice::default.suggest.placeholder', ['label' => $this->label()]);

        $options['data-format-no-matches'] =
            $options['data-format-no-matches'] ??
            trans('backoffice::default.suggest.no_matches');

        $options['data-format-searching'] =
            $options['data-format-searching'] ??
            trans('backoffice::default.suggest.searching');

        $options['data-format-input-too-short'] =
            $options['data-format-input-too-short'] ??
            trans('backoffice::default.suggest.input_to_short', ['min' => $this->minimumInputLength()]);

        $this->changeOptions($options);
    }
}
