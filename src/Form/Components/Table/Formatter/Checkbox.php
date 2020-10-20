<?php
namespace dimonka2\flatform\Form\Components\Table\Formatter;

class Checkbox extends BaseFormatter
{
    protected $class = 'text-center';
    protected $value = 1;
    protected $checkbox_class;
    protected $wire_event;
    protected $wire_model;
    protected $key_field = 'id';

    protected function read(array $definition)
    {
        $this->readSettings($definition, [
            'value',
            'checkbox_class',
            'wire_event',
            'wire_model',
            'key_field',
        ]);
        parent::read($definition);
    }

    protected function transformValue($value)
    {
        $checkbox = ['checkbox', 'col' => false, 'label' => false, 'checked' => $value, 'value' => $value];
        if($this->checkbox_class) $checkbox['+class'] = $this->checkbox_class;
        if($this->key_field){
            $key = $this->row[$this->key_field];
            if($this->wire_model) $checkbox['wire:model'] = "{$this->wire_model}.{$key}";
            if($this->wire_event) {
                $new_state = json_encode($value ? null : $this->value);
                $checkbox['wire:click'] = "\$emit('{$this->wire_event}', {$key}, {$new_state})";
            }
        }
        // debug($checkbox);
        return [$checkbox];
    }
}
