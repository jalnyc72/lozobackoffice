<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

use Digbang\Backoffice\Controls\ControlFactory;
use Digbang\Backoffice\Support\Collection as DigbangCollection;
use Illuminate\Http\Request;

class ActionFactory implements ActionFactoryInterface
{
    /**
     * @var ControlFactory
     */
    protected $controlFactory;

    /**
     * @var Request|null
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
    public function link($target, $label = null, $options = [], $view = null, $icon = null)
    {
        $view = $view ?: 'backoffice::actions.link';
        $action = new Action($this->controlFactory->make($view, $label, $options), $target, $icon);

        if ($this->request && !$target instanceof \Closure) {
            $parts = parse_url($target);
            if (isset($parts['query'])) {
                $target = str_replace([$parts['query'], '?'], '', $target);
            }

            $action->setActive($this->request->url() === $target);
        }

        return $action;
    }

    /**
     * {@inheritdoc}
     */
    public function form($target, $label, $method = 'POST', $options = [], $view = null)
    {
        $view = $view ?: 'backoffice::actions.form';

        return new Form(
            $this->controlFactory->make($view, $label, $options),
            $target,
            $method);
    }

    /**
     * {@inheritdoc}
     */
    public function modal($id, $title, $form, $label, $options = [], $icon = null)
    {
        return new Modal(
            $id,
            $title,
            $form,
            $this->controlFactory->make('backoffice::actions.modal', $label, $options),
            $icon
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
    public function dropdown($label, $options = [], $view = null, $icon = null)
    {
        $view = $view ?: 'backoffice::actions.dropdown';

        $options = array_merge([
            'class' => 'btn btn-default'
        ], $options);

        $options['class'] .= ' dropdown-toggle';

        $action = new Composite(
            $this->controlFactory->make($view, $label, $options),
            $this,
            new DigbangCollection(),
            $icon
        );

        return $action;
    }
}
