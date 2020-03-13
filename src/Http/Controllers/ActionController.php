<?php

namespace dimonka2\flatform\Http\Controllers;

use Illuminate\Routing\Controller;
use dimonka2\flatform\Http\Requests\ActionRequest;


class ActionController extends Controller
{
    public function __invoke(ActionRequest $request)
    {
        $action = $request->action();
        if(!$action) return abort(400);
        return $action->execute();
    }

    public function getForm(ActionRequest $request)
    {
        $action = $request->action();
        if(!$action) return abort(400);
        return $action->form();
    }
}
