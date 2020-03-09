<?php

namespace dimonka2\flatform\Http\Requests;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Actions\Action;
use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
{
    protected $action;

    protected function getActionClass()
    {
        $resolver = Flatform::config("flatform.actions.resolver");
        if(class_exists($resolver)) {
            return (new $resolver())($this->name, $this);
        } elseif (is_callable($resolver)) {
            return call_user_func_array($resolver, [$this->name, $this]);
        }
    }

    public function action(): ?Action
    {
        if(!$this->action) {
            if(!$this->has('name')) return null;
            $class = $this->getActionClass();
            if(!class_exists($class)) return null;
            $this->action = Action::make($class);
        }
        return $this->action;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $action = $this->action();
        return $action ? $action->autorize($this) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
