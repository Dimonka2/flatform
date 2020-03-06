<?php

namespace dimonka2\flatform\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\ActionRequest;


class ActionController extends Controller
{
    public function __invoke(ActionRequest $request)
    {
        $action = $request->action();
        if(!$action) return abort(404);
        return $action->execute($request);
    }
}
