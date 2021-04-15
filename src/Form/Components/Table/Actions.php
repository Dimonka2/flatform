<?php

namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Traits\SettingReaderTrait;


class Actions implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use ItemsTrait;
    use SettingReaderTrait;
    protected $table;
    protected $dropdown = ['dropdown', 'group', 'size' => 'sm',
        'icon' => 'fa fa-play', 'toggle', 'shadow',
        'color' => 'outline-secondary'];
    protected $rowDropdown = ['table-dropdown'];
    protected $selectionType = 'a';
    protected $dropdownType = 'dd-item';
    protected $rowType = 'dd-item';

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->items = collect();
    }

    public function read(array $definition)
    {
        $this->readSettings($definition, [
            'dropdown', 'rowDropdown'
        ]);
        foreach ($definition as $action) {
            if(is_array($action)) $this->items[] = new TableAction($action);
        }
    }

    public function getInfoActions()
    {
        $actions = [];
        foreach($this->items as $item) {
            if(!$item->disabled && $item->hasPosition('info')){
                $action = $item->getElement();
                if(!isset($action['type'])) $action['type'] = $this->selectionType;
                $actions[] = $action;
            }
        }
        return $actions;
    }

    public function getRowActions()
    {

    }

    public function getSelectionActions()
    {
        $inlineActions = [];
        $ddActions = [];
        foreach($this->items as $item) {
            if(!$item->disabled){
                if( $item->isSelectionAction() ){
                    $action = $item->getElement();
                    if($item->hasPosition("selection")) {
                        if(!isset($action['type'])) $action['type'] = $this->selectionType;
                        $inlineActions[] = $action;
                    }
                    if($item->hasPosition("dropdown")) {
                        if(!isset($action['type'])) $action['type'] = $this->dropdownType;
                        $ddActions[] = $action;
                    }
                }
            }
        }
        $actions = $inlineActions;
        if(count($ddActions) > 0) {
            $dropdown = $this->dropdown;
            $dropdown['items'] = $ddActions;
            $actions[] = $dropdown;
        }
        // debug($actions);
        return $actions;
    }

    public function getActionIndex($name)
    {
        return $this->items->search(function ($item) use($name) {
            return ($item->name == $name) or ($item->as == $name);
        });
    }

    public function getAction($index): ?TableAction
    {
        if(is_integer($index)) {
            return $this->items[$index];
        }
        $key = $this->getActionIndex($index);
        if($key !== false) return $this->items[$key];
        return null;
    }

    public function getActionEx($index, &$key)
    {
        if(is_integer($index)) {
            $key = $index;
        } else {
            $key = $this->getActionIndex($index);
        }
        if($key !== false) return $this->items[$key];
        return;
    }


}
