<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class Date extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $format;
    protected $emptyString;

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'format', 'emptyString',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        if(!$value) return $this->emptyString;
        $value = \Carbon\Carbon::parse($value);
        $value = $this->format ? $value->format($this->format) : $value->diffForHumans();
        return $value;
    }
}
