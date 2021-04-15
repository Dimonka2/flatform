<?php

namespace dimonka2\flatform\Livewire;

use Closure;
use Livewire\Component;
use Illuminate\Support\Str;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Traits\WithPagination;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Traits\TableSearchQuery;
use dimonka2\flatform\Form\Contracts\IContainer;
use dimonka2\flatform\Form\Components\Table\Table;

class TableComponent extends Component
{
    use TableSearchQuery;
    use WithPagination;

    protected $idField = 'id';

    public $selectAll = false;
    public $search;
    public $searchDebounce = 500;
    public $order = "";
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

        return view($this->getView('table'))
            ->with('host', $this)
            ->with('table', $table);
    }

    protected function processDynamicProperties(Table $table)
    {
        if(!$table->getQuery()){
            if (method_exists(static::class, $method = 'getQuery')) {
                $table->setQuery($this->{$method}());
            }
        }
        if(!$table->hasSelect()){
            if (method_exists(static::class, $method = 'getSelect')) {
                $table->setSelect($this->{$method}());
            }
        }
        if(!$table->hasDetails()){
            if (method_exists(static::class, $method = 'getDetails')) {
                $table->setDetails($this->{$method}());
            }
        }

        if(!$table->hasActions()){
            if (method_exists(static::class, $method = 'getActions')) {
                $table->setActions($this->{$method}());
            }
        }

        if(!$table->getFilters()){
            if (method_exists(static::class, $method = 'getFilters')) {
                $table->setFilters($this->{$method}());
            }
        }

    }

    protected function ensureTable($prepareRows = false)
    {
        if(!$this->table) $this->table = $this->getTable();
        if(!$prepareRows || $this->rowsReady) return;
        $table = $this->table;
        if($this->order) $table->setOrder($this->order);
        $this->processDynamicProperties($table);

        $table->setLength($this->length);
        if( $table->hasSearch() ) $table->setSearch($this->search);
        $this->redirectFilters($table);
        if($table->hasSelect()) {
            $this->addSelectCheckbox($table);
        }
        $table->buildRows();
        if($this->page > 1 && $table->getRowCount() == 0) {
            // try to rebuid
            $this->page = 1;
            $table->setPage(1);
            $table->buildRows();
        }
        $this->rowsReady = true;
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
                ['button', 'color' => 'clean',  'size' => 'sm', 'class' => 'btn-icon-md',
                    '_data' => ['_item' => function (IContainer $element, $row) {
                        $id = $row->{$this->idField};
                        $element->setAttribute('wire:click.prevent',
                             'showDetails(' . json_encode($id) . ')');
                        $row->_expanded = ($this->expanded[$id] ?? false);
                        $element->readItems([
                            ['i', 'class' => $row->_expanded ? 'fa fa-caret-up' : 'fa fa-caret-down',]
                        ]);
                    }]
                ]
            );
        }

    }

    public function sortColumn($columnName)
    {
        $order = $this->order;
        if(!$order) {
            $table = $this->getTable();
            $order = $table->setOrder($table->getOrder())->getOrder();
        }

        if (!($order[$columnName] ?? false)) {
            if(!isset($table)) $table = $this->getTable();
            $sort = $table->getColumnSort($columnName);
            if($sort) {
                $this->order = [$columnName => $sort];
            }
        } else {
            $order = $order[$columnName];
            $this->order = [$columnName => ($order == 'DESC') ? 'ASC' : 'DESC'];
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

    public function getElement($element)
    {
        switch ($element) {
            case 'info':
                return $this->getInfo();
            case 'filters':
                return $this->renderFilters();
            case 'top':
            case 'bottom':
                if (method_exists(static::class, $method = 'get' . Str::ucfirst($element))) {
                    return $this->{$method}();
                }
                return;
        }
    }


    protected function renderFilters()
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

    protected function afterPageChange()
    {
        if($this->scrollUp) {
            $table = $this->getTable();
            $this->emit('navigateTo', '#' . $table->getId());
        }

    }

    /**
     * Get the value of info
     */
    protected function getInfo()
    {
        $table = $this->table;
        $selected = $table->getSelected();
        if(!$selected && $this->info === false ) return;

        if($selected){
            $this->info = trans_choice('flatform::table.selected', $selected, ['value' => $selected]);
            // render actions

            $this->info = [['span', 'text' => $this->info, 'class' => 'mr-4']];
            $this->info = array_merge($this->info, $table->getSelectionActions());

        } else {
            $items = $table->getModels();
            if($items) {
                $total = $items->total();
                $this->info = __('flatform::table.info',[
                    'first' => $items->firstItem(),
                    'last' => $items->lastItem(),
                    'total' => $total]);
                $count = $table->getCount();
                if($total != $count) $this->info .= trans_choice('flatform::table.filtered', $count, ['filtered' => $count]);
            }
        }
        $infoActions = $this->table->getInfoActions();
        if(count($infoActions) > 0) {
            $infoActions = $this->table->renderItem([['span', 'class' => 'mr-4', $infoActions]]);
        } else $infoActions = null;
        return  $infoActions . $this->table->renderItem($this->info);
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

    public function getPublicPropertiesDefinedBySubClass()
    {
        $data = parent::getPublicPropertiesDefinedBySubClass();
        $data = $this->addPaginatorPublicProperties($data);
        $data = $this->addSearchPublicProperties($data);
        return $data;
    }

    public function __get($property)
    {
        // debug('Get: ' . $property);
        $p = $this->paginator__get($property);
        if(is_array($p)) return $p[0];
        $p = $this->search__get($property);
        if(is_array($p)) return $p[0];
        return parent::__get($property);
    }

    public function __set($property, $value)
    {
        // debug('Set: ' . $property, $value);
        if($this->paginator__set($property, $value)) return;
        if($this->search__set($property, $value)) return;
        parent::__set($property, $value);
    }

}
