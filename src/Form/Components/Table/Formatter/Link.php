<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use Illuminate\Support\Str;

class Link extends BaseFormatter
{
    protected $route;
    protected $idField;
    protected $idFieldRequired;
    protected $emptyString;
    protected $limit;
    protected $tag = 'a';


    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'route', 'idField', 'limit', 'emptyString', 'idFieldRequired'
        ]);
        parent::read($definition);

    }

    protected function render($value)
    {
        if(!$value) $value = $this->emptyString;
        $limited = $value;
        if($this->limit) $limited = Str::limit($limited, $this->limit, '...');
        $id = $this->row[$this->idField];
        if($id || !$this->idFieldRequired ) {
            $href = route($this->route, $id);
        } else {
            $href = '#';
        }
        $link = ['a', 'href' => $href, 'text' => $limited];
        if($limited != $value) $link['tooltip'] = $value;
        $value = [$link];
        return $value;
    }


}
