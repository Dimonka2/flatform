<?php

namespace dimonka2\flatform\Form\Inputs;

use dimonka2\flatform\Form\Input;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;
use Flatform;



class Summernote extends Input
{
    protected function addAssets()
    {
        if(!Flatform::isIncluded('summernote')) {
            Flatform::include('summernote');
            $path = config('flatform.assets.summernote.path');
            Flatform::addCSS(config('flatform.assets.summernote.css'), $path);
            Flatform::addJS(config('flatform.assets.summernote.js'), $path);
            return $this->context->renderView(
                view(config('flatform.assets.summernote.view')));
        }
    }
    
    protected function render()
    {
        $html = $this->addAssets();
        return Form::textarea($this->name, $this->value,
            $this->getOptions([])) . $html;
    }
}
