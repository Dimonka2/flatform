<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString    = 'search';
    protected $filterQueryString    = 'filtered';
    protected $orderQueryString     = 'sort';
    protected $defaultOrder;

    public function initializeTableSearchQuery()
    {
        $table = $this->getTable();
        $defaultOrder = $table->setOrder($table->getOrder())->getOrder();
        $this->defaultOrder = $defaultOrder;
        // fix for livewire 2.0
        if(property_exists($this, 'updatesQueryString')) {
            $qsProperty = 'updatesQueryString';
        } else {
            $qsProperty = 'queryString';
        }
        $this->{$qsProperty}['length'] = ['except' => '10'];
        $this->{$qsProperty}[$this->searchQueryString] = ['except' => ''];
        $this->{$qsProperty}[$this->filterQueryString] = ['except' => []];
        $this->{$qsProperty}[$this->orderQueryString] = $defaultOrder ? ['except' => $defaultOrder] : [];

        $this->search   = request()->query($this->searchQueryString , $this->search);
        $this->filtered = request()->query($this->filterQueryString , $this->filtered);
        $this->order = request()->query($this->orderQueryString , $this->order);
    }

    protected function search__get($property)
    {

        if ($property == $this->searchQueryString) {
            return [$this->search];
        } elseif ($property == $this->filterQueryString) {
            return [$this->filtered];
        }elseif ($property == $this->orderQueryString) {
            return [$this->order];
        }
    }

    protected function search__set($property, $value)
    {
        if ($property == $this->searchQueryString) {
            $this->search = $value;
            return $this;
        } elseif($property == $this->filterQueryString) {
            $this->filtered = $value;
            return $this;
        } elseif($property == $this->orderQueryString) {
            $this->order = $value;
            return $this;
        }
    }

    protected function addSearchPublicProperties($data)
    {
        $data[$this->searchQueryString] = $this->search;
        $data[$this->filterQueryString] = $this->filtered;
        $data[$this->orderQueryString]  = $this->order ? $this->order : $this->defaultOrder;
        return $data;
    }

}
