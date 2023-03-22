<?php

namespace Digbang\Backoffice\Inputs;

use Digbang\Backoffice\Controls\ControlFactory;
use Digbang\Backoffice\Support\Collection as DigbangCollection;
use Digbang\Backoffice\Uploads\FileUploadHandler;
use Illuminate\Http\Request;

class InputFactory implements InputFactoryInterface
{
    /**
     * @var ControlFactory
     */
    protected $controlFactory;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param ControlFactory $controlFactory
     * @param Request|null   $request
     */
    public function __construct(ControlFactory $controlFactory, Request $request = null)
    {
        $this->controlFactory = $controlFactory;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function text($name, $label = null, $options = [])
    {
        return $this->make(
            'backoffice::inputs.text',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function dropdown($name, $label = null, $data = [], $options = [])
    {
        $options = $this->buildOptions($options, null);

        if (isset($options['sortable']) && $options['sortable'] == true) {
            $options['multiple'] = 'multiple';

            $class = array_get($options, 'class', '');
            $options['class'] = trim("$class sortable");

            unset($options['sortable']);
        }

        if (isset($options['multiple'])) {
            if (!isset($options['id'])) {
                $options['id'] = trim($name, '[]');
            }

            if (!ends_with($name, '[]')) {
                $name .= '[]';
            }
        }

        $value = $this->request ? $this->request->get(str_replace('[]', '', $name)) : null;

        return new DropDown(
            $this->controlFactory->make(
                'backoffice::inputs.dropdown',
                $label,
                $options),
            $name,
            $value,
            $data
        );
    }
    /**
     * {@inheritdoc}
     */
    public function suggest($name, $label = null, $route, $options = [])
    {
        $options = $this->buildOptions($options, null);

        // Option sortable
        if (isset($options['sortable']) && $options['sortable'] == true) {
            $options['multiple'] = 'multiple';

            $class = array_get($options, 'class', '');
            $options['class'] = trim("$class sortable");
        }

        $value = $this->request ? $this->request->get($name) : null;
        $data = $this->request ? $this->request->get($name . '_json') : null;

        $suggest = new Suggest(
            $this->controlFactory->make(
                'backoffice::inputs.suggest',
                $label,
                $options),
            $name,
            $value,
            $route,
            $data
        );

        // Option minLength
        if (isset($options['minLength'])) {
            $minLength = array_get($options, 'minLength', '');
            $suggest->setMinimumInputLength($minLength);
        };

        return $suggest;
    }

    /**
     * {@inheritdoc}
     */
    public function button($name, $label, $options = [])
    {
        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        if (!isset($options['id'])) {
            $options['id'] = $name;
        }

        return $this->make(
            'backoffice::inputs.button',
            $label,
            $options,
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function checkbox($name, $label, $options = [])
    {
        return $this->make('backoffice::inputs.checkbox', $label, $options, $name);
    }

    /**
     * {@inheritdoc}
     */
    public function integer($name, $label, $options = [])
    {
        return $this->make(
            'backoffice::inputs.number',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function date($name, $label, $options = [])
    {
        $options = $this->buildOptions($options, $label);

        $class = array_get($options, 'class', '');

        $options['class'] = trim("$class form-date");

        return $this->make(
            'backoffice::inputs.text',
            $label,
            $options,
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function datetime($name, $label, $options = [])
    {
        return $this->make(
            'backoffice::inputs.datetime',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function time($name, $label, $options = [])
    {
        return $this->make(
            'backoffice::inputs.time',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function password($name, $label, $options = [])
    {
        return $this->make(
            'backoffice::inputs.password',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function textarea($name, $label = null, $options = [])
    {
        return $this->make(
            'backoffice::inputs.textarea',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function wysiwyg($name, $label = null, $options = [])
    {
        $options['class'] = trim(array_get($options, 'class', '') . ' form-control wysiwyg');

        return $this->make(
            'backoffice::inputs.textarea',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function collection()
    {
        return new Collection($this, new DigbangCollection());
    }

    /**
     * {@inheritdoc}
     */
    public function composite($name, Collection $collection, $label = '', $options = [])
    {
        return new Composite(
            $name,
            $this->controlFactory->make('backoffice::inputs.composite', $label, $options),
            $collection
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hidden($name, $options = [])
    {
        $input = $this->make(
            'backoffice::inputs.hidden',
            $name,
            $options,
            $name
        );

        // By default, hidden inputs are hidden ;-)
        $input->hide();

        return $input;
    }

    /**
     * {@inheritdoc}
     */
    public function colorPicker($name, $label, $options = [])
    {
        return $this->make(
            'backoffice::inputs.colorpicker',
            $label,
            $this->buildOptions($options, $label),
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boolean($name, $label, $options = [])
    {
        return $this->dropdown($name, $label, [
            ''      => '',
            'true'  => trans('backoffice::default.yes'),
            'false' => trans('backoffice::default.no'),
        ], $options);
    }

    /**
     * {@inheritdoc}
     */
    public function file($name, $label, $options = [])
    {
        return new File(
            new FileUploadHandler(),
            $this->controlFactory->make(
                'backoffice::inputs.file',
                $label,
                $options
            ),
            $name,
            $this->request ? $this->request->get($name) : null
        );
    }

    /**
     * {@inheritdoc}
     */
    public function literal($name, $content, $options = [])
    {
        $options['html'] = $content;

        if (!isset($options['id'])) {
            $options['id'] = $name;
        }

        return $this->make(
            'backoffice::inputs.literal',
            $name,
            $options,
            $name
        );
    }

    /**
     * {@inheritdoc}
     */
    public function tagger($name, $label = null, $data = [], $options = [])
    {
        $options = $this->buildOptions($options, null);

        $value = $this->request ? $this->request->get(str_replace('[]', '', $name)) : null;

        return new Tagger(
            $this->controlFactory->make(
                'backoffice::inputs.tagger',
                $label,
                $options
            ),
            $name,
            $value,
            $data
        );
    }

    /**
     * {@inheritdoc}
     */
    public function view($label, $view, $with = [])
    {
        return new View(
            $this->controlFactory->make(
                $view,
                $label,
                []
            ),
            $label,
            $with
        );
    }

    /**
     * {@inheritdoc}
     */
    public function translatable(array $languages)
    {
        return new TranslatableInputFactory($this, $this->controlFactory->getViewFactory(), $languages);
    }

    /**
     * @param string $view
     * @param string $label
     * @param array  $options
     * @param string $name
     *
     * @return InputInterface
     */
    protected function make($view, $label, $options, $name)
    {
        return new Input(
            $this->controlFactory->make(
                $view,
                $label,
                $options
            ),
            $name,
            $this->request ? $this->request->get($name) : null
        );
    }

    /**
     * @param array  $options
     * @param string $label
     *
     * @return array
     */
    protected function buildOptions($options, $label)
    {
        $options = array_add($options, 'class', 'form-control');

        if (!$label) {
            return $options;
        }

        return array_add($options, 'placeholder', $label);
    }
}
