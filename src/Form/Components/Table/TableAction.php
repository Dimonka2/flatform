<?php

namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Traits\SettingReaderTrait;

class TableAction
{
    use SettingReaderTrait;
    protected $table;

    protected $name;           // field name, assigned to input
    protected $title;          // filter title or label
    protected $type;           // filter type: checkbox, select, text
    protected $disabled;       // disables filter
    protected $value;          // current value
    protected $list;           // item list for select
    protected $filterFunction; // filter callback

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'name', 'title', 'type', 'disabled', 'value', 'list', 'filterFunction'
        ]);
        return parent::read($definition);
    }

}
