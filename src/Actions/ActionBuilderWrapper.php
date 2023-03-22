<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

/**
 * @method $this addClass($className)
 * @method $this addRel($rel)
 * @method $this addTarget($target)
 * @method $this addDataConfirm($message)
 * @method $this addDataToggle($message)
 * @method $this addDataPlacement($message)
 * @method $this addTitle($message)
 */
class ActionBuilderWrapper implements ActionBuilderInterface
{
    /**
     * @var ActionBuilder
     */
    private $actionBuilder;

    /**
     * @var Collection
     */
    private $actionCollection;

    /**
     * @param ActionBuilder $actionBuilder
     * @param Collection    $collection
     */
    public function __construct(ActionBuilder $actionBuilder, Collection $collection)
    {
        $this->actionBuilder = $actionBuilder;
        $this->actionCollection = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function to($target)
    {
        $this->actionBuilder->to($target);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function labeled($label)
    {
        $this->actionBuilder->labeled($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function view($view)
    {
        $this->actionBuilder->view($view);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function icon($icon)
    {
        $this->actionBuilder->icon($icon);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add($attribute, $value)
    {
        $this->actionBuilder->add($attribute, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asLink()
    {
        $link = $this->actionBuilder->asLink();

        $this->actionCollection->add($link);

        return $link;
    }

    /**
     * @param string $func
     * @param array  $args
     *
     * @return $this
     */
    public function __call($func, $args)
    {
        call_user_func_array([$this->actionBuilder, $func], $args);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function asForm($method = 'POST')
    {
        $form = $this->actionBuilder->asForm($method);

        $this->actionCollection->add($form);

        return $form;
    }
}
