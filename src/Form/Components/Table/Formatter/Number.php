<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\Flatform;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class Number extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $decimals = 0;
    protected $emptyString;

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'decimals', 'emptyString',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        if(!$value) return $this->emptyString;
        $value = is_numeric($value) ? number_format($value, $this->decimals) : $value;
        return  Flatform::render([
            ['div', 'class' => 'text-right', 'text' => $value],
        ]);
    }


}
