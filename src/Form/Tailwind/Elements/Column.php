<?php

namespace dimonka2\flatform\Form\Tailwind\Elements;

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

    }

    public function getOptions(array $keys)
    {
        if($this->col   ) $this->addClass('col-span-'    . $this->col);
        if($this->sm    ) $this->addClass('sm:col-span-' . $this->sm);
        if($this->md    ) $this->addClass('md:col-span-' . $this->md);
        if($this->lg    ) $this->addClass('lg:col-span-' . $this->lg);
        if($this->xl    ) $this->addClass('xl:col-span-' . $this->xl);
        if (!$this->col && !$this->sm && !$this->md && !$this->lg  && !$this->xl) {
            $this->addClass(FlatformService::config('flatform.form.col', 'col-span-6'));
       }
       return parent::getOptions($keys);
   }

    public function getTag()
    {
        return 'div';
    }

}
