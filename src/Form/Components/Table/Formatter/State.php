<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class State extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
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
