<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

class Check extends BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $checked = 'fa fa-check text-success';
    protected $unchecked = 'fa fa-times text-danger';

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'checked',
            'unchecked',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        $class = !!$value ? $this->checked : $this->unchecked;
        $html = FlatformService::render([['i', 'class' => $class  ]]);
        // debug($value . ' = ' . $html);
        return $html;
    }
}
