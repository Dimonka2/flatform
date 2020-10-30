<?php
namespace dimonka2\flatform\Traits;

trait RouteTrait
{
    protected static function getRouteUrl($route = null)
    {
        if ($route) {
            if(!is_array($route)) return route($route);
            if(count($route) == 1) return route(head($route));
            $parameters = array_slice($this->route, 1);
            return route(head($route), $parameters);
        }

        return url()->current();
    }
}