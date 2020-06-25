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

    public $selectAll = false;
    public $search;
    public $searchDebounce = 500;
    public $order;
    public $length = 10;
    public $class;
    public $expanded = [];
    public $filtered = [];
    public $selected = [];

    protected $info;  // make it false to exclude info column

    protected $listeners = [
        'showDetails' => 'showDetails',
    ];

    protected $table;
    protected $rowsReady;
    protected $scrollUp = true;

    public function render()
    {
        $this->ensureTable(true);
        $table = $this->table;

        if($table->hasDetails()) {
            $this->addDetailsButton($table);
        }
        // $this->addRowCallback($table);
        // debug($table);
        return view($this->getView('table'))
            ->with('host', $this)
            ->with('table', $table);
    }

    protected function ensureTable($prepareRows = false)
    {
        if(!$this->table) $this->table = $this->getTable();
        if(!$prepareRows || $this->rowsReady) return;
        $table = $this->table;
        if($this->order) $table->setOrder($this->order);
        $table->setLength($this->length);
        $table->setSearch($this->search);
        $this->redirectFilters($table);
        if($table->hasSelect()) {
            $this->addSelectCheckbox($table);
        }
        $table->buildRows();
        $this->rowsReady = true;
    }

    protected function addRowCallback($table)
    {
        $table->setRowRenderCallback(function($row, $html) {
            $id = 'row_' . $row->{$this->idField} . '_' .
                (int)$row->_details . '_' . (int) $row->_selected;
            // logger($html);

            $response = \Livewire\Livewire::mount('flatform.table-row', ['row' => $html, '_id' => $id]);
            return $response->dom;

            return Flatform::render([
                ['livewire', 'name' => 'flatform.table-row', 'with' => ['row' => $html, '_id' => $id]]
            ]);
        });
    }

    protected function addSelectCheckbox($table)
    {
        $select = $table->getSelect();
        if (!$select->checkbox) {
            $select->setCheckbox(
                ['input', 'template' => false,
                    '_attributes' => ['type' => 'checkbox'],
                    'autocomplete' => 'off',
                    '_data' => ['_item' => function (IElement $element, $row) {
                        //debug($row);
                        $id = $row->{$this->idField};
                        $element->id = $this->id . '_' . $id;
                        $element->setAttribute('wire:model', 'selected.' . $id);
                        if($this->selected[$id] ?? false) $element->setAttribute('checked', '');
                    }],
                ]
            );

            $select->setHeaderCheckbox(
                ['input', 'wire:model' => 'selectAll',
                    'autocomplete' => 'off', 'template' => false,
                    'tooltip' => 'Select all',
                    '_attributes' => ['type' => 'checkbox'],
                ]
            );
        }

        $select->setSelectCallback(function($row) {
            $id = $row->{$this->idField};
            return $this->selected[$id] ?? false;
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

        if( $table->hasSelect()) {
            $select = $table->getSelect();
            $html .= Flatform::render([
                ['include', 'name' => $view, 'with' => [
                    'table' => $table,
                    'width' => $select->width,
                    'column' => null,
                    'title' => Flatform::context()->renderItem($select->getTitle()),
                ]]
            ]);
        }

        if( $table->hasDetails() ){
            // add details column
            $details = $table->getDetails();
            $html .= Flatform::render([
                ['include', 'name' => $view, 'with' => [
                    'table' => $table,
                    'width' => $details->width,
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

    // inject function to link currently selected filter value
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
            $this->ensureTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }

    public function nextPage()
    {
        $this->page = $this->page + 1;
        if($this->scrollUp) {
            $this->ensureTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }

    public function gotoPage($page)
    {
        $this->page = $page;
        if($this->scrollUp) {
            $this->ensureTable();
            $this->emit('navigateTo', '#' . $this->table->getId());
        }
    }


    /**
     * Get the value of info
     */
    public function getInfo()
    {
        if($this->info !== false) {
            $table = $this->table;
            $selected = $table->getSelected();
            if($selected){
                $this->info = trans_choice('flatform::table.selected', $selected, ['value' => $selected]);
                // render actions

                $this->info = [['span', 'text' => $this->info, 'class' => 'mr-4']];
                $this->info = array_merge($this->info, $table->getSelectionActions());

            } else {
                $items = $table->getModels();
                $total = $items->total();
                $this->info = __('flatform::table.info',[
                    'first' => $items->firstItem(),
                    'last' => $items->lastItem(),
                    'total' => $total]);
                $count = $table->getCount();
                if($total != $count) $this->info .= trans_choice('flatform::table.filtered', ['filtered' => $count]);
            }
        }

        return $this->table->renderItem($this->info);
    }

    // select/deselect all rows
    public function updatedSelectAll($value)
    {
        if(!$value){
            foreach ($this->selected as $key => $value) {
                $this->selected[$key] = 0;
            }
            return;
        }
        $this->ensureTable(true);
        $table = $this->table;
        $ids = $table->getVisibleValues($this->idField);

        foreach ($ids as $id) {
            if($id) $this->selected[$id] = 1;
        }
        $table->processSelection();

    }

    protected function getSelected($models = false)
    {
        $selected = [];
        $this->ensureTable(true);
        $table = $this->table;
        foreach ($table->getRows() as $row) {
            if($row->_selected) $selected[] = $models ? $row->_item : $row->{$this->idField};
        }
        return $selected;
    }

    protected function reload()
    {
        $this->rowsReady = false;
        $this->ensureTable(true);
    }
}
