<?php

namespace dimonka2\flatform\Livewire;

use Closure;
use Livewire\Component;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Actions\Action;

class ActionComponent extends Component
{
    public $formData;
    public $url;
    protected $form;
    protected $listeners = [
        'runAction' => 'runAction',
    ];

    public function mount()
    {
        $this->url = request()->url();
    }

    public function render()
    {
        $form = $this->form;
        return view("flatform::livewire.action-form")
            ->with('form', $form)
            ->with('host', $this);
    }

    protected static function extractArray($value)
    {
        if(!preg_match("/^\[(.*)\]$/", $value, $matches)) return [$value];
        return explode(',', $matches[1]);
    }

    public function formSubmit($data)
    {
        if(!is_array($data)){
            return $this->error('Unable to process submited form data!');
        }
        // debug($data);
        // convert form data to array
        $params = [];
        foreach ($data as $item) {
            $name = $item['name'];
            $value = $item['value'];
            if(substr($name, -2) == '[]') {
                $name = substr($name, 0, -2);
                $params[$name] = self::extractArray($value);
            } else {
                $params[$name] = $value;
            }
        }
        $actionClass = $params['_action'] ?? false;
        if(!$actionClass || !$this::isAction($actionClass)) {
            return $this->error('Action is not recognized:' . $actionClass);
        }
        $action = new $actionClass($params);

        if(!$action->autorize()) {
            return $this->error('Action is not authorized!');
        }
        $response = $action->execute();
        return $this->processResponse($response, $action);
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
            return $this->error('Action is not recognized:' . $actionClass);
        }
        $action = new $actionClass($params);

        if(!$action->autorize()) {
            return $this->error('Action is not authorized!');
        }

        $form = $action->getForm();
        if($form) {
            $this->form = $action->prepareForm($form, 'flatform-action');
            return;
        }
        $response = $action->execute();
        return $this->processResponse($response, $action);
    }

    public function getID()
    {
        return $this->id;
    }

    protected function error($message, $title = "Error!")
    {
        return $this->displayMessage($message, $title, 'error');
    }

    public function reload()
    {
        $reload = Flatform::getReload();
        if($reload instanceof Closure) return $reload();
        return redirect()->to($this->url);
    }

    protected function processResponse($response, $action)
    {
        if(!$response) return $this->displayMessage('No response from the action..', $action->getTitle(), 'error');
        $response = $response->getOriginalContent();
        $result = $response['result'] ?? '';
        $message = $response['message'] ?? null;
        if( $result == 'error' ) return $this->error($message, 'Action error!');
        $redirect = $response['redirect'] ?? '';
        if($redirect) {
            if($redirect == Action::reload) return $this->reload();
            return redirect()->to($redirect);
        }
        if($message) return $this->displayMessage($message, 'Info');
    }

    protected function displayMessage($message, $title, $type = 'info')
    {
        $icon = null;
        switch ($type) {
            case 'info':
                $icon = 'fa fa-info-circle';
                break;

            case 'error':
                $icon = 'fa fa-exclamation-circle';
                break;
        }
        $alert = [
            ['alert', 'title' => $message, 'icon' => $icon, 'color' => ($type == 'info' ? 'light' : 'danger' )]
        ];
        $modal = ['modal', $alert, 'id' => Action::modalID,
            'title' => $title,
            'footer' => [
            ['button', 'title' => 'Close',
                'data-dismiss' => "modal" ],
        ]];

        // render complete form
        $form = ['form', 'id' => Action::formID, [
            $modal,
        ]];
        $this->form = [$form];
    }
}
