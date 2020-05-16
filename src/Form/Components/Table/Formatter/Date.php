<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class Date extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $format;

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'format',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        $value = \Carbon\Carbon::parse($value);
        $value = $this->format ? $value->format($this->format) : $value->diffForHumans();
        return $value;
    }
}
