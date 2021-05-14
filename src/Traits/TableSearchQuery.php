<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString    = 'search';
    protected $filterQueryString    = 'filtered';
    protected $orderQueryString     = 'sort';
    protected $lengthQueryString    = 'length';
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
        $this->{$qsProperty}[$this->lengthQueryString] = ['except' => 10];
        $this->{$qsProperty}[$this->searchQueryString] = ['except' => ''];
        $this->{$qsProperty}[$this->filterQueryString] = ['except' => []];
        $this->{$qsProperty}[$this->orderQueryString] = $defaultOrder ? ['except' => $defaultOrder] : [];

        $this->length   = intval(request()->query($this->lengthQueryString , $this->length));
        $this->search   = request()->query($this->searchQueryString , $this->search);
        $this->filtered = request()->query($this->filterQueryString , $this->filtered);
        $this->order = request()->query($this->orderQueryString , $this->order);
    }

    protected function search__get($property)
    {

        if ($property == $this->searchQueryString) {
            return [$this->search];
        }
        if ($property == $this->filterQueryString) {
            return [$this->filtered];
        }
        if ($property == $this->orderQueryString) {
            return [$this->order];
        }
        if ($property == $this->lengthQueryString) {
            return [$this->length];
        }
    }

    protected function search__set($property, $value)
    {
        if ($property == $this->searchQueryString) {
            $this->search = $value;
            return $this;
        }
        if($property == $this->filterQueryString) {
            $this->filtered = $value;
            return $this;
        }
        if($property == $this->orderQueryString) {
            $this->order = $value;
            return $this;
        }
        if($property == $this->lengthQueryString) {
            $this->length = intval($value);
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
