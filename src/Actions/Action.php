<?php
namespace dimonka2\flatform\Actions;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Http\Requests\ActionRequest;

class Action implements Contract
{
    public const name = '';
    public const reload = 'reload';
    public const back   = 'back';
    protected $params;

    public function execute()
    {

    }

    public function init(ActionRequest $request)
    {

    }

    public function autorize()
    {
        return false;
    }

    protected function response($message, $result = 'ok', $redirect = null)
    {
        $response = ['result' => $result, 'message' => $message];
        if($redirect) $response['redirect'] = $redirect;
        return response()->json($response);
    }

    protected function ok($message, $redirect = null)
    {
        return $this->response($message, 'ok', $redirect);
    }

    protected function error($message, $redirect = null)
    {
        return $this->response($message, 'error', $redirect);
    }

    public function __construct(?array $params = [])
    {
        $this->params = $params;
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
}
