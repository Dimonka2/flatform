<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use Illuminate\Support\Carbon;

class Date extends BaseFormatter
{
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
        $value = Carbon::parse($value);
        $value = $this->format ? $value->format($this->format) : $value->diffForHumans();
        return $value;
    }
}
