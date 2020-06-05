<?php

namespace dimonka2\flatform\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Components\Table\Table;

class TableComponent extends Component
{
    use WithPagination;

    public $search;
    public $info;  // make it false to exclude info column
    public $order;
    public $length = 10;
    public $class;

    public $filtered = [];

    protected $table;

    public function render()
    {
        if(!$this->table) $this->table = $this->getTable();
        $table = $this->table;
        if($this->order) $table->setOrder($this->order);
        $table->setLength($this->length);
        $table->setSearch($this->search);
        $table->buildRows();

        if($this->info !== false) {
            $items = $table->getModels();
            $total = $items->total();
            $this->info = 'Showing ' . $items->firstItem() . ' to ' . $items->lastItem() . ' of ' . $total . ' entries';
            $count = $table->getCount();
            if($total != $count) $this->info .= ' (filtered from ' . $count . ' total entries)';
        }

        // debug($table);
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
        return FlatformService::config('flatform.livewire.table_view', 'flatform::livewire.table');
    }

    protected function getTable(): ?Table
    {
        return null;
    }

    public function getLengthOptions()
    {
        $options = $this->table->getLengthOptions();
        $res = [];
        foreach($options as $option) {
            $res[$option] = $option;
        }
        return $res;
    }

}
