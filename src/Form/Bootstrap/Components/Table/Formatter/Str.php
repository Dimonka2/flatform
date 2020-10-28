<?php
namespace dimonka2\flatform\Form\Bootstrap\Components\Table\Formatter;

use Illuminate\Support\Str as StrIlluminate;

class Str extends BaseFormatter
{
    protected $limit = 0;
    protected $emptyString;

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'limit', 'emptyString',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        if(!$value) return $this->emptyString;
        if(!$this->limit) return $value;
        $limited = StrIlluminate::limit($value, $this->limit, '...');
        if($limited == $value) return $value;
        $link = ['a', 'text' => $limited, 'tooltip' => $value];
        $value = [$link];
        return $value;
    }
}
