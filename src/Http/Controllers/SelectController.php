<?php

namespace dimonka2\flatform\Http\Controllers;

use Illuminate\Http\Request;
use dimonka2\flatform\Flatform;
use Illuminate\Routing\Controller;
use dimonka2\flatform\Form\Contracts\IAJAXSelect;

class SelectController extends Controller
{
    protected static function getSelect($name): IAJAXSelect
    {
        $resolver = Flatform::config("flatform.selects.resolver");
        if(class_exists($resolver)) {
            return (new $resolver())($this->name, $this);
        } elseif (is_callable($resolver)) {
            return call_user_func_array($resolver, [$this->name, $this]);
        }
    }

    public function __invoke(Request $request)
    {
        if(!$request->has('name')) return abort(400);
        $select = self::getSelect($request->has('name'));
        if(!$select) return abort(400);
        return $select->getItems($request);
    }

}
