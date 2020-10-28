<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table\Formatter;

class Check extends BaseFormatter
{
    protected $class = 'text-center';
    protected $checked = 'fa fa-check text-success';
    protected $unchecked = 'fa fa-times text-danger';

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'checked',
            'unchecked',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        $class = !!$value ? $this->checked : $this->unchecked;
        $html = [['i', 'class' => $class  ]];
        // debug($value . ' = ' . $html);
        return $html;
    }
}
