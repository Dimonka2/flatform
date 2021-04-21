<?php
namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Flatform;
use dimonka2\flatform\FlatformService;
use dimonka2\flatform\Form\ElementFactory;
use dimonka2\flatform\Form\ElementContainer;
use dimonka2\flatform\Form\Contracts\IElement;
use dimonka2\flatform\Form\Components\Table\Formatter\ElementMapping;

class Table extends ElementContainer
{
    use ColumnsTrait;
    use RowBuilderTrait;
    use OrderTrait;
    use FiltersTrait;

    protected $page;
    protected $length = 10;
    protected $selected;
    protected $lengthOptions = [10, 20, 30, 50, 100];
    protected $evenOddClasses = ['even', 'odd'];
    protected $addSelect; // fields that needs to be added to the query
    protected $query;
    protected $search;
    protected $select;
    protected $actions;
    protected $details;
    protected $formatters = [];     // this is a lookup list for column formatters
    protected $formatFunction;      // this is a table td element format function
    protected $info;                // make it false to exclude info column
    protected $links;               // make it false to hide links
    protected $rowRenderCallback;   // this needed for a livewire table to separate table from rows rendering
        // parameters ($row, $html, $details = false)
    protected $rowPreRenderCallback;   // allows to update row definition before it is rendered to HTML
        // parameters ($row, array $def): array
    protected $count;
    protected $filtered_count;
    protected $models;

    // use FiltersTrait;

    public function render()
    {
        // build rows
        if($this->query) $this->buildRows();
        /* 1. render surrounding:
                Search,
                pagination,
                length choice,
                filters,
                actions
            2. render table:
                Header columns with sorting and check all,
                body rows with checks and details
        */
        $declaration = [
            ['row', [
                ['col', ], // search
                ['col', ], // filter/actions
                ['col', 'col' => 12, [
                    ['table',
                        'id' => $this->id,
                        'class' => $this->class,
                        [
                            ['thead', [ ['tr', [
                                ['onRender' => function($item){return $this->renderHead();},]
                                ]]
                            ]],
                            ['tbody', 'onRender' => function($item){return $this->renderBody($item);},]
                        ]
                    ],
                ]],
                ['col', 'md' => 4, '+class' => 'p-3',
                    'hide' => $this->info === false,
                    'text' => $this->formatPosition()], // page length
                ['col', 'md' => 8, [
                    ['div', '+class' => 'float-md-right mt-2',
                        'hide' => $this->links === false,
                        'text' => $this->links !== false ? $this->getLinks() : null], // paginator
                ]],
            ]],
        ];

        return FlatformService::render($declaration);
    }

    protected function renderHead()
    {
        return $this->columns->render($this->context);
    }

    protected function renderBody(IElement $item)
    {
        $tbody = $this->attributes->tbody;
        if(is_array($tbody)) {
            foreach ($tbody as $key => $value) {
                $item->setAttribute($key, $value);
            }
            $tbody['type'] = 'tbody';
        } else {
            $tbody = ['tbody'];
        }
        $tbody['text'] = $this->rows->render($this->context);
        return Flatform::render([$tbody]);
    }

    protected function formatPosition()
    {
        $page = $this->page ?? 0;
        $count = count($this->rows);
        $total = $this->count ? ' (' . $this->count . ')' : '';
        if($count == 0) return $total;
        $start = (($page - 1) * $this->length + 1);
        return  $start . '-' . ($start + $count - 1)  . $total;
    }

    protected function read(array $element)
    {
        $this->readSettings($element, [
            'order',
            'page',
            'length',
            'lengthOptions',
            'evenOddClasses',
            'query',
            'search',
            'formatters',
            'formatFunction',
            'info',
            'links',
            'rowRenderCallback',
            'rowPreRenderCallback',
            'addSelect',
        ]);

        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

        $rows = self::readSingleSetting($element, 'rows');
        $this->createRows($rows ?? []);


        $filters = self::readSingleSetting($element, 'filters');
        $this->createFilters($filters ?? []);

        $details = self::readSingleSetting($element, 'details');
        if(is_array($details)) $this->createDetails($details);

        $select = self::readSingleSetting($element, 'select');
        if(is_array($select)) $this->createSelect($select);

        $actions = self::readSingleSetting($element, 'actions');
        if(is_array($actions)) $this->createActions($actions);

        if($this->order) $this->preprocessOrder();

        if($this->addSelect && !is_array($this->addSelect)) $this->addSelect = [$this->addSelect];

        parent::read($element);
        $this->requireID();
    }


    public function getColumnFormatter($name)
    {
        if(is_string($name) && ($this->formatters[$name] ?? false)) return $this->formatters[$name];
        return ElementMapping::map($name) ;
    }

    protected function createDetails(?array $details)
    {
        if(!$details) {
            $this->details = null;
            return;
        }
        $this->details = new TableDetails($this);
        $this->details->read(ElementFactory::preprocessElement($details, false));
        return $this->details;
    }

    public function hasDetails()
    {
        return $this->details && !$this->details->getDisabled();
    }

    public function hasSearch()
    {
        return $this->search !== false;
    }

    protected function createSelect(?array $select)
    {
        if(!$select) {
            $this->select = null;
            return;
        }
        $this->select = new TableSelect($this);
        $this->select->read(ElementFactory::preprocessElement($select, false));
        return $this->select;
    }

    public function hasSelect()
    {
        return $this->select && !$this->select->getDisabled();
    }

    public function hasFormat()
    {
        return !!$this->formatFunction;
    }

    protected function createActions(?array $actions)
    {
        if(!$actions) {
            $this->actions = null;
            return;
        }
        $this->actions = new Actions($this);
        $this->actions->read($actions);
        return $this->actions;
    }

    public function hasActions()
    {
        return !!$this->actions;
    }

    public function getInfoActions()
    {
        return $this->hasActions() ? $this->actions->getInfoActions() : [];
    }

    public function getSelectionActions()
    {
        return $this->hasActions() ? $this->actions->getSelectionActions() : [];
    }

    public function getRowActions()
    {
        return $this->hasActions() ? $this->actions->getRowActions() : [];
    }

    /**
     * Get the value of order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set the value of order
     *
     * @return  self
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $this->preprocessOrder();
        return $this;
    }

    /**
     * Get the value of length
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the value of length
     *
     * @return  self
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    public function getLinks($paginationView = null)
    {
        return $this->models ? $this->models->links($paginationView) : null;
    }

    /**
     * Get the value of filter
     */
    public function getFilter()
    {
        return $this->filters;
    }

    /**
     * Set the value of filter
     *
     * @return  self
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get the value of formatFunction
     */
    public function getFormatFunction()
    {
        return $this->formatFunction;
    }

    /**
     * Set the value of formatFunction
     *
     * @return  self
     */
    public function setFormatFunction($formatFunction)
    {
        $this->formatFunction = $formatFunction;

        return $this;
    }

    /**
     * Get the value of models
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Get the value of search
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Set the value of search
     *
     * @return  self
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get the value of lengthOptions
     */
    public function getLengthOptions()
    {
        return $this->lengthOptions;
    }

    /**
     * Set the value of lengthOptions
     *
     * @return  self
     */
    public function setLengthOptions($lengthOptions)
    {
        $this->lengthOptions = $lengthOptions;

        return $this;
    }


    /**
     * Get the value of count
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Get the value of details
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set the value of details
     *
     * @return  self
     */
    public function setDetails($details)
    {
        $this->createDetails($details);

        return $this;
    }

    /**
     * Get the value of query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the value of query
     *
     * @return  self
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of rowRenderCallback
     */
    public function getRowRenderCallback()
    {
        return $this->rowRenderCallback;
    }

    /**
     * Set the value of rowRenderCallback
     *
     * @return  self
     */
    public function setRowRenderCallback($rowRenderCallback)
    {
        $this->rowRenderCallback = $rowRenderCallback;

        return $this;
    }

    public function getId()
    {
        if(!$this->id) $this->requireID();
        return $this->id;
    }

    /**
     * Get the value of select
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * Set the value of select
     *
     * @return  self
     */
    public function setSelect($select)
    {
        $this->createSelect($select);

        return $this;
    }

    /**
     * Get the value of evenOddClasses
     */
    public function getEvenOddClasses(): ?array
    {
        return $this->evenOddClasses;
    }

    /**
     * Set the value of evenOddClasses
     *
     * @return  self
     */
    public function setEvenOddClasses(?array $evenOddClasses)
    {
        $this->evenOddClasses = $evenOddClasses;

        return $this;
    }

    /**
     * Get the value of selected
     */
    public function getSelected()
    {
        return $this->selected;
    }

    public function processSelection()
    {
        $this->selected = 0;
        if($this->hasSelect()) {
            $selectCallback = $this->select->getSelectCallback();
            if($selectCallback instanceof Closure) {
                foreach($this->rows as $row) {
                    $row->_selected = $selectCallback($row);
                    if($row->_selected) $this->selected ++;
                }

            }
        }

    }

    /**
     * Get the value of actions
     */
    public function getActions()
    {
        return $this->actions;
    }

    public function setActions($actions)
    {
        $this->createActions($actions);

        return $this;
    }

    /**
     * Get the value of page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set the value of page
     *
     * @return  self
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

	public function getAddSelect() {
		return $this->addSelect;
	}

	public function setAddSelect($addSelect) {
        // convert to array
        if(!is_array($addSelect)) $addSelect = [$addSelect];
        $this->addSelect = $addSelect;
        return $this;
	}


    /**
     * Get the value of rowPreRenderCallback
     */
    public function getRowPreRenderCallback()
    {
        return $this->rowPreRenderCallback;
    }

    /**
     * Set the value of rowPreRenderCallback
     *
     * @return  self
     */
    public function setRowPreRenderCallback($rowPreRenderCallback)
    {
        $this->rowPreRenderCallback = $rowPreRenderCallback;

        return $this;
    }
}
