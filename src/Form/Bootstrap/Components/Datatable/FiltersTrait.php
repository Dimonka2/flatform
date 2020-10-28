<?php

namespace dimonka2\flatform\Form\Bootstrap\Components\Datatable;

trait FiltersTrait
{
    protected $filters;   // collection of DTFilter objects

    protected function createFilters(array $filters)
    {
        $this->filters = new DatatableFilters($this);
        foreach($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    public function addFilter(array $definition)
    {
        $filter = $this->createElement($definition, 'dt-filter');
        $this->filters[] = $filter;
        return $filter;
    }

    public function getFilter($index): ?DTFilter
    {
        return $this->Filters->getFilter($index);
    }

    /**
    * Get the value of Filters
    */
    public function getFilters()
    {
        return $this->filters;
    }


}
