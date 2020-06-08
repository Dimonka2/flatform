<?php

namespace dimonka2\flatform\Livewire;

use Livewire\Component;
use dimonka2\flatform\Actions\Action;

class ActionComponent extends Component
{
    public $formData;
    protected $form;
    protected $listeners = [
        'runAction' => 'runAction',
    ];

    public function render()
    {
        $form = $this->form;

        return view("flatform::livewire.action-form")
            ->with('form', $form)
            ->with('host', $this);
    }

    public function formSubmit($data)
    {
        if(!is_array($data)){
            debug("No action: " . $actionClass);
        }
    }

    protected static function isAction($actionClass)
    {
        if(!class_exists($actionClass)) return;
        $parents = class_parents( $actionClass);
        return in_array(Action::class, $parents);
    }

    public function runAction($actionClass, $params)
    {

        if(!$this::isAction($actionClass)) {
            // show error
            debug("No action: " . $actionClass);
            return;
        }
        $action = new $actionClass($params);

        if(!$action->autorize()) {
            debug('Not authorized', $action);
            return;
        }

        $form = $action->getForm();
        if($form) {
            $this->form = $action->prepareForm($form, 'flatform-action');
            debug($this->form);
            return;
        }
        $response = $action->execute()->getOriginalContent();
        debug($response);
    }

    public function getID()
    {
        return $this->id;
    }
}
