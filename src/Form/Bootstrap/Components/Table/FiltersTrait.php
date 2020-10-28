<?php

namespace dimonka2\flatform\Form\Bootstrap\Components\Table;

use dimonka2\flatform\Form\ElementFactory;

trait FiltersTrait
{
    protected $filters = [];   // collection of DTFilter objects

    protected function createFilters($filters)
    {
        foreach($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    public function addFilter(?array $definition)
    {
        $filter = new TableFilter(ElementFactory::preprocessElement($definition), $this->context);
        $filter->setTable($this);
        $this->filters[] = $filter;
        return $filter;
    }

    public function getFilter($index): ?TableFilter
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

    public function setFilters(?array $filters)
    {
        $this->createFilters($filters ?? []);
        return $this;
    }


    public function renderFilters()
    {
        $html = '';
        foreach($this->filters as $filter){
            if(!$filter->getDisabled()){
                $value = $this->filtered[$filter->getName()] ?? null;
                $html .= $filter->renderFilter($value);
            }
        }
        return $html;
    }

}
