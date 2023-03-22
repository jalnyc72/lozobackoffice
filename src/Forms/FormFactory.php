<?php

namespace Digbang\Backoffice\Forms;

use Digbang\Backoffice\Actions\ActionFactory as ActionFactory;
use Digbang\Backoffice\Inputs\InputFactory as InputFactory;
use Illuminate\Session\Store;

class FormFactory
{
    /**
     * @var InputFactory
     */
    protected $inputFactory;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var Store
     */
    protected $session;

    /**
     * @param InputFactory  $inputFactory
     * @param ActionFactory $actionFactory
     * @param Store         $session
     */
    public function __construct(InputFactory $inputFactory, ActionFactory $actionFactory, Store $session)
    {
        $this->inputFactory = $inputFactory;
        $this->actionFactory = $actionFactory;
        $this->session = $session;
    }

    /**
     * @param string|\Closure $target
     * @param string|\Closure $label
     * @param string          $method
     * @param string          $cancelAction
     * @param array           $options
     *
     * @return Form
     */
    public function make($target, $label, $method = 'POST',  $cancelAction = '', $options = [])
    {
        return new Form(
            'backoffice::form',
            $this->actionFactory->form($target, $label, $method, []),
            $this->inputFactory->collection(),
            $this->session,
            $cancelAction,
            $options
        );
    }
}
