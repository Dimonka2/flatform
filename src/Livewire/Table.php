<?php

namespace dimonka2\flatform\Livewire;

use Livewire\Component;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Components\Table\Table as FlatTable;

class Table extends Component
{

    public $search;
    public $info;  // make it false to exclude info column
    public $order;
    public $length = 10;
    public $page;

    protected $table;


    public function render()
    {
        $table = $this->table ? $this->table : $this->getTable();
        if($this->order) $table->setOrder($this->order);
        $table->setLength($this->length);
        $table->buildRows();
        //debug($table);
        return view($this->getView())
            ->with('host', $this)
            ->with('table', $table);
    }

    public function sortColumn($columnName)
    {
        if (!($this->order[$columnName] ?? false)) {
            $this->table = $this->getTable();
            $sort = $this->table->getColumnSort($columnName);
            if($sort) {
                $this->order = [$columnName => $sort];
            }
        } else {
            $order = $this->order[$columnName];
            $this->order[$columnName] = ($order == 'DESC') ? 'ASC' : 'DESC';
        }

    }

    protected function getView()
    {
        return FlatformService::config('flatform.livewire.table_view');
    }

    protected function getTable(): ?FlatTable
    {
        return null;
    }



}
