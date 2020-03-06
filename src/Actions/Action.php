<?php
namespace dimonka2\flatform\Actions;

use App\Http\Requests\ActionRequest;

abstract class Action
{
    public const name = '';
    protected $params;

    abstract public function execute(ActionRequest $request);

    public function __construct(?array $params)
    {
        $this->params = $params;
    }

    public static function make($action): ?Action
    {
        if(is_array($action)){
            if(isset($action['class'])){
                $class = $action['class'];
            } elseif(isset($action[0])){
                $class = $action[0];
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
}
