<?php

namespace dimonka2\flatform\Form\Inputs;

use Flatform;

class Summernote extends Textarea
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

}
