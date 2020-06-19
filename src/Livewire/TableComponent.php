<?php

namespace dimonka2\flatform\Livewire;

use Closure;
use dimonka2\flatform\Flatform;
use Livewire\Component;
use Livewire\WithPagination;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\Components\Table\Table;
use dimonka2\flatform\Form\Contracts\IElement;

class TableComponent extends Component
{
    use WithPagination;

    protected $idField = 'id';

    public $search;
    public $searchDebounce = 500;
    public $info;  // make it false to exclude info column
    public $order;
    public $length = 10;
    public $class;
    public $expanded = [];
    public $filtered = [];

    protected $listeners = [
        'showDetails' => 'showDetails',
    ];

    protected $table;
    protected $scrollUp = true;

    public function render()
    {
        if(!$this->table) $this->table = $this->getTable();
        $table = $this->table;
        if($this->order) $table->setOrder($this->order);
        $table->setLength($this->length);
        $table->setSearch($this->search);
        $this->redirectFilters($table);
        $table->buildRows();

        if($table->hasDetails()) {
            $this->addDetailsButton($table);
        }
        // $this->addRowCallback($table);

        if($this->info !== false) {
            $items = $table->getModels();
            $total = $items->total();
            $this->info = 'Showing ' . $items->firstItem() . ' to ' . $items->lastItem() . ' of ' . $total . ' entries';
            $count = $table->getCount();
            if($total != $count) $this->info .= ' (filtered from ' . $count . ' total entries)';
        }

        // debug($table);
        return view($this->getView('table'))
            ->with('host', $this)
            ->with('table', $table);
    }

    protected function addRowCallback($table)
    {
        $table->setRowRenderCallback(function($row, $html, $details = false) {
            $id = 'row_' . $row->{$this->idField} . '_' . (int)$details;
            // debug($id);

            // $response = \Livewire\Livewire::mount('flatform.table-row', ['row' => $html, 'id' => $id]);
            // return $response->dom;

            return Flatform::render([
                ['livewire', 'name' => 'flatform.table-row', 'with' => ['row' => $html, 'id' => $id]]
            ]);
        });
    }

    protected function addDetailsButton($table)
    {
        $details = $table->getDetails();
        if (!$details->expander) {
            $details->setExpander(
                ['button', 'color' => 'clean',
                    '_data' => ['_item' => function (IElement $element, $row) {
                        //debug($row);
                        $id = $row->{$this->idField};
                        $element->setAttribute('wire:click.prevent',
                             'showDetails(' . json_encode($id) . ')');
                        if($this->expanded[$id] ?? false) $row->_expanded = 1;
                    }],
                    'size' => 'sm', 'class' => 'btn-icon-md', [
                        ['i',
                            'class' =>'fa fa-caret-down',
                            '_data' => ['_item' => function (IElement $element, $row) {
                                if($row->_expanded) $element->class = 'fa fa-caret-up';
                            }],
                            ]
                ]]
            );
        }

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

    public function showDetails($id)
    {
        if($this->expanded[$id] ?? false) {
            unset($this->expanded[$id]);
        } else {
            $this->expanded[$id] = 1;
        }
    }

    protected function getView($viewName)
    {
        return FlatformService::config('flatform.livewire.' . $viewName, 'flatform::livewire.' . $viewName);
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

    public function renderHeader(Table $table)
    {
        $order = $table->getOrder();

        $html = "";
        $view = $this->getView('table-th');

        if( $table->hasDetails() ){
            // add details column
            $details = $table->getDetails();
            $html .= Flatform::render([
                ['include', 'name' => $view, 'with' => [
                    'table' => $table,
                    'column' => null,
                    'title' => Flatform::context()->renderItem($details->getTitle()),
                ]]
            ]);

        }
        foreach($table->getColumns() as $column) {
            if($column->visible()){
                $html .= Flatform::render([
                    ['include', 'name' => $view, 'with' => [
                        'table' => $table,
                        'column' => $column,
                        'order' => $order[$column->name] ?? false,
                    ]]
                ]);
            }
        }
        return $html;

    }

    // inject function to link currently
    protected function redirectFilters($table)
    {
        foreach($table->getFilters() as $filter) {
            if( !$filter->getDisabled() ) {
                $filterFunction = $filter->getFilterFunction();
                if($filterFunction instanceof Closure) {
                    $filter->setFilterFunction(function($query, $value)
                        use ($filterFunction, $filter) {
                        $newValue = $this->filtered[$filter->getName()] ?? null;
                        $value = $newValue ? $newValue : $value;
                        return $filterFunction($query, $value);
                    });
                }
            }
        }

    }

    public function renderFilters()
    {
        $html = '';
        $view = $this->getView('table-filter');
        foreach($this->table->getFilters() as $filter) {
            if( !$filter->getDisabled() ) $html .= Flatform::render([
                ['include', 'name' => $view, 'with' => [
                    'table' => $this->table,
                    'value' => $this->filtered[$filter->getName()] ?? null,
                    'filter' => $filter,
                ]]
            ]);

        }
        return $html;
    }

    public function previousPage()
    {
        $this->page = max(1, $this->page - 1);
        if($this->scrollUp) {
            $this->table = $this->getTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }

    public function nextPage()
    {
        $this->page = $this->page + 1;
        if($this->scrollUp) {
            $this->table = $this->getTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }

    public function gotoPage($page)
    {
        $this->page = $page;
        if($this->scrollUp) {
            $this->table = $this->getTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }

}
