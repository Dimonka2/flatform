<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;

use dimonka2\flatform\Flatform;

class Date extends Input
{
    protected function addAssets()
    {
        if(!Flatform::isIncluded('datepicker')){
            Flatform::include('datepicker');
            $path = config('flatform.assets.datepicker.path');
            Flatform::addCSS(config('flatform.assets.datepicker.css'), $path);
            Flatform::addJS(config('flatform.assets.datepicker.js'), $path);
            return $this->context->renderView(
                view(config('flatform.assets.datepicker.view'))
            );
        }
    }

    public function getTag()
    {
        return 'text';
    }
}
