<?php

namespace dimonka2\flatform\Form;

use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IContext;

// Bootstrap column

class Column extends ElementContainer
{
    protected $col;
    protected $col_md;
    protected $col_lg;
    protected $col_xl;

    protected function read(array $element)
    {
        $this->readSettings($element, ['col', 'col-md', 'col-lg', 'col-xl']);
        parent::read($element);
        if(!is_null($this->col)) $this->addClass('col-' . $this->col);
        if(!is_null($this->col_md)) $this->addClass('col-md-' . $this->col_md);
        if(!is_null($this->col_lg)) $this->addClass('col-lg-' . $this->col_lg);
        if(!is_null($this->col_xl)) $this->addClass('col-xl-' . $this->col_xl);
        if (
            is_null($this->col) && is_null($this->col_md) && is_null($this->col_md) && is_null($this->col_md)
            ) $this->addClass(config('flatform.form.col', 'col-6'));
        // dd($this);
    }

    public function getTag()
    {
        return 'div';
    }

}
