<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table\Formatter;

class Number extends BaseFormatter
{
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
        return  [
            ['div', 'class' => 'text-right', 'text' => $value],
        ];
    }


}
