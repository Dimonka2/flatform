<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use Illuminate\Support\Str;

class Link extends BaseFormatter
{
    protected $route;
    protected $idField;
    protected $emptyString;
    protected $limit;
    protected $tag = 'a';


    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'route', 'idField', 'limit', 'emptyString',
        ]);
        parent::read($definition);

    }

    protected function render($value)
    {
        if(!$value) $value = $this->emptyString;
        $limited = $value;
        if($this->limit) $limited = Str::limit($limited, $this->limit, '...');
        $link = ['a', 'href' => route($this->route, $this->row[$this->idField]), 'text' => $limited];
        if($limited != $value) $link['tooltip'] = $value;
        $value = [$link];
        return $value;
    }


}
