<?php

namespace dimonka2\flatform\Form\Bootstrap\Elements;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementContainer;

// Bootstrap column

class Column extends ElementContainer
{
    public $col;
    public $sm;
    public $md;
    public $lg;
    public $xl;

    protected function read(array $element)
    {
        $this->readSettings($element, ['col', 'sm', 'md', 'lg', 'xl']);
        parent::read($element);
        if($this->col   ) $this->addClass('col-'    . $this->col);
        if($this->sm    ) $this->addClass('col-sm-' . $this->sm);
        if($this->md    ) $this->addClass('col-md-' . $this->md);
        if($this->lg    ) $this->addClass('col-lg-' . $this->lg);
        if($this->xl    ) $this->addClass('col-xl-' . $this->xl);

        if (!$this->col && !$this->sm && !$this->md && !$this->lg  && !$this->xl) {
             $this->addClass(FlatformService::config('flatform.form.col', 'col-6'));
        }
    }

    public function getTag()
    {
        return 'div';
    }

}
