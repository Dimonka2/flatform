<?php

namespace dimonka2\flatform\Form\Elements;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

// Bootstrap column

class Column extends ElementContainer
{
    protected $col;
    protected $md;
    protected $lg;
    protected $xl;
    protected $xs;

    protected function read(array $element)
    {
        $this->readSettings($element, ['col', 'md', 'lg', 'xl', 'xs']);
        parent::read($element);
        if($this->col   ) $this->addClass('col-'    . $this->col);
        if($this->md    ) $this->addClass('col-md-' . $this->md);
        if($this->lg    ) $this->addClass('col-lg-' . $this->lg);
        if($this->xl    ) $this->addClass('col-xl-' . $this->xl);
        if($this->xs    ) $this->addClass('col-xs-' . $this->xs);

        if (!$this->col && !$this->md && !$this->lg  && !$this->xl && !$this->xs) {
             $this->addClass(config('flatform.form.col', 'col-6'));
        }
    }

    public function getTag()
    {
        return 'div';
    }

}
