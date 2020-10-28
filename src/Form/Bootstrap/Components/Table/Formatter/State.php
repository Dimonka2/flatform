<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table\Formatter;

class State extends BaseFormatter
{
    protected $icon = true;
    protected $emptyString;
    protected $flatstate;

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'icon', 'emptyString',
        ]);
        parent::read($definition);
        $this->flatstate = app('flatstates');
    }

    protected function transformValue($value)
    {
        if(!$value) return $this->emptyString;
        $value = (is_numeric($value) && $this->flatstate) ? $this->flatstate->formatState($value, $this->icon) : $value;
        return  $value;
    }


}
