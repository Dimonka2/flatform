<?php

namespace dimonka2\flatform\Form\Navs;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Navs\Menu;
use dimonka2\flatform\Form\Contracts\IContext;
use Form;

class Menu extends ElementContainer
{

    protected function read(array $element)
    {
        // $this->readSettings($element, ['badge', 'active']);
        parent::read($element);

    }


    public function renderElement()
    {
        if(!$this->hidden) {
            return $this->renderLink();
        }
    }

}
