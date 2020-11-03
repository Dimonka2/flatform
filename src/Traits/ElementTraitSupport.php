<?php
namespace dimonka2\flatform\Traits;

trait ElementTraitSupport
{
    protected $elementMethodMap = [];
    
    protected function initMethodMap()
    {
        foreach (class_uses_recursive($this) as $trait) {
            $hooks = [
                'read',
                'getAttributes',
                'render',
            ];
            $baseName = class_basename($trait);
            foreach ($hooks as $hook) {
                $method = $hook.$baseName;

                if (method_exists($this, $method)) {
                    $this->elementMethodMap[$hook][] = $method;
                }
            }
        }
    }

    protected function callTraitFunction($name, $params) 
    {
        // debug(print_r($params, true));
        $methods = $this->elementMethodMap[$name] ?? [];
        foreach ($methods as $method) {
            $params = call_user_func_array([$this, $method], [$params]);
        }
        return $params;
    }
}