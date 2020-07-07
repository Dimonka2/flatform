<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Traits\SettingReaderTrait;
use dimonka2\flatform\Form\Components\Table\IColumnFormat;

abstract class BaseFormatter implements IColumnFormat
{
    use SettingReaderTrait;
    protected $column;
    protected $row;
    protected $class;
    protected $tag = 'div';

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'class',
        ]);
    }

    public function __construct(array $definition = [])
    {
        $this->read($definition);
    }

    protected function getValueClass($value)
    {
        return $this->class;
    }

    protected function transformValue($value)
    {
        return $value;
    }

    protected function render($value)
    {
        $form = [[$this->tag,
            'class' => $this->getValueClass($value),
            'text' => $this->transformValue($value)]];
        return FlatformService::render($form);
    }

    public function __invoke($value, $column, $row)
    {
        $this->column = $column;
        $this->row = $row;
        return $this->render($value);
    }

    /**
     * Get the value of class
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the value of class
     *
     * @return  self
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }
}
