<?php
namespace dimonka2\flatform\Actions;

use Illuminate\Support\Fluent;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Form\Contracts\IForm;

class Action implements Contract
{
    public const modalID = 'flatform-action';
    public const formID = 'flatform-action-form';
    public const name = '';
    public const reload = 'reload';
    public const noform = 'noform';
    protected const confirmText = 'Run action';
    protected const cancelText = 'Cancel';
    protected const confirmColor = 'danger';
    protected const cancelColor = 'secondary';
    protected $params;
    protected $redirect;
    protected $form;

    public function execute()
    {

    }

    public function init(array $params)
    {
    }

    public function autorize()
    {
        return false;
    }

    public function prepareForm($form, $modalID = null)
    {
        $modal = ['modal', $form, 'id' => $modalID ? $modalID : self::modalID,
            'title' => $this->getTitle(),
            'footer' => [
            ['button', 'color' => $this->getConfirmColor(), 'title' => $this->getConfirmText(),
                '+class' => 'confirm'],
            ['button', 'color' => $this->getCancelColor(), 'title' => $this->getCancelText(),
                'data-dismiss' => "modal" ],
        ]];
        $size = $this->getFormSize();
        if($size) $modal['size'] = $size;

        // render complete form
        $form = ['form', 'id' => self::formID, [
            ['hidden', 'name' => 'name', 'value' => static::name],
            ['hidden', 'name' => '_action', 'value' => get_class($this)],
            $modal,
        ]];

        if(Flatform::livewire() ){
            // $form['wire:submit.prevent'] = 'formSubmit';
        } else {
            $form['url'] = route('flatform.action');
        }

        $form = Flatform::context()->createElement($form);
        return $this->addFormOptions($form);
    }

    public function form()
    {
        $form = $this->getForm();
        if(!$form) return $this->ok('', self::noform);

        $form = $this->prepareForm($form);

        $this->form = Flatform::render([$form]);
        return $this->ok('Confirm', '');
    }

    protected function getTitle()
    {
        return static::name;
    }

    protected function getConfirmText()
    {
        return static::confirmText;
    }

    protected function getConfirmColor()
    {
        return static::confirmColor;
    }

    protected function getCancelText()
    {
        return static::cancelText;
    }

    protected function getCancelColor()
    {
        return static::cancelColor;
    }

    protected function getFormSize()
    {
        return null;
    }

    protected function addFormOptions(IForm $form)
    {
        return $form;
    }


    protected function response($message, $result = 'ok')
    {
        $response = ['result' => $result, 'message' => $message];
        if($this->form) $response['form'] = $this->form;
        if($this->redirect) $response['redirect'] = $this->redirect;
        return response()->json($response);
    }

    protected function ok($message, $redirect = null)
    {
        $this->redirect = $redirect;
        return $this->response($message, 'ok');
    }

    protected function error($message, $redirect = null)
    {
        $this->redirect = $redirect;
        return $this->response($message, 'error');
    }

    public function __construct(?array $params = [])
    {
        if(is_array($params)) $this->init($params);
        $this->params = new Fluent($params);
    }

    public static function make($action): ?Action
    {
        if(is_object($action)) return $action;
        if(is_array($action)){
            if(isset($action['class'])){
                $class = $action['class'];
            } elseif(isset($action[0])){
                $class = $action[0];
                if(isset($action[1]) && is_array($action[1])) $params = $action[1];
            } else {
                return null;
            }
            if(isset($action['params'])){
                $params = $action['params'];
            }
            return new $class($params ?? []);

        } elseif(class_exists($action)){
            return new $action();
        }
    }

    public function __invoke()
    {
        return self::formatClick($this);
    }


    public static function formatClick($action)
    {
        if(is_object($action)) {
            $name = $action->getName();
            $params = $action->getParams();

            if(Flatform::livewire()) return 'window.livewire.emit("runAction", ' .
                json_encode(get_class($action)) . ', ' . json_encode($params) . ')';

        } elseif(is_array($action)){
            if(isset($action['name'])){
                $name = $action['name'];
            } elseif(isset($action[0])){
                $class = $action[0];
                if(isset($action[1]) && is_array($action[1])) $params = $action[1];
            } else {
                return null;
            }
            if(isset($action['params'])){
                $params = $action['params'];
            }
            return new $class($params ?? []);

        } else{
            $name = $action;
        }
        return 'return ' . Flatform::config('flatform.actions.js-function', 'ffactions') .
            '.run("' . $name . '"'. ($params ?? false ? ', ' . json_encode($params) : '') . ');';
    }

    /**
     * Get the value of params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the value of form
     *
     * @return  self
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get the value of redirect
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Set the value of redirect
     *
     * @return  self
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get the value of form
     */
    public function getForm()
    {
        return $this->form;
    }
}
