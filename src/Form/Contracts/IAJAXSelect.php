<?php

namespace dimonka2\flatform\Form\Contracts;

use Illuminate\Http\Request;

interface IAJAXSelect extends IElement
{
    public function getItems(Request $request);

}
