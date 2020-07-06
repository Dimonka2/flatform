<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use Illuminate\Support\Str;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class Link extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $route;
    protected $idField;
    protected $emptyString;
    protected $limit;


    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'route', 'idField', 'limit', 'emptyString',
        ]);
        parent::read($definition);

    }

    protected function transformValue($value)
    {
        if(!$value) $value = $this->emptyString;
        $limited = $value;
        if($this->limit) $limited = Str::limit($limited, $this->limit, '...');
        $link = ['a', 'href' => route($this->route, $this->row[$this->idField]), 'text' => $limited];
        if($limited != $value) $link['tooltip'] = $value;
        $value = Flatform::render([$link]);
        return  $value;
    }


}
