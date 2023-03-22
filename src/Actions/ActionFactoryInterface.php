<?php
declare(strict_types=1);

namespace Digbang\Backoffice\Actions;

interface ActionFactoryInterface
{
    /**
     * @param string|callable $target
     * @param string|null     $label
     * @param array           $options
     * @param null            $view
     * @param null            $icon
     *
     * @return Action
     */
    public function link($target, $label = null, $options = [], $view = null, $icon = null);

    /**
     * @param string $target
     * @param string $label
     * @param string $method
     * @param array  $options
     *
     * @return Form
     */
    public function form($target, $label, $method = 'POST', $options = [], $view = null);

    /**
     * @param string|callable $id
     * @param string|callable $title
     * @param Form|callable $form
     * @param string $label
     * @param array $options
     * @param string|null $icon
     *
     * @return Modal
     */
    public function modal($id, $title, $form, $label, $options = [], $icon = null);

    /**
     * @return Collection
     */
    public function collection();

    public function dropdown($label, $options = [], $view = 'backoffice::actions.dropdown', $icon = null);
}
