<?php

namespace App\Http\Requests;

use App\Actions\Action;
use dimonka2\flatform\Flatform;
use Illuminate\Foundation\Http\FormRequest;

class ActionRequest extends FormRequest
{
    protected $action;

    public function action(): ?Action
    {
        if(!$this->action) {
            if(!$this->has('name')) return null;
            $class = Flatform::config("actions.commands." . $this->name);
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
        return true;
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
