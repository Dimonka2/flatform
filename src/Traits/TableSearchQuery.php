<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString    = 'search';
    protected $filterQueryString    = 'filtered';
    protected $orderQueryString     = 'order';

    public function initializeTableSearchQuery()
    {
        $table = $this->getTable();
        $defaultOrder = $table->setOrder($table->getOrder())->getOrder();

        // fix for livewire 2.0
        if(property_exists($this, 'updatesQueryString')) {
            $qsProperty = 'updatesQueryString';
        } else {
            $qsProperty = 'queryString';
        }
        $this->{$qsProperty} = array_merge([$this->searchQueryString => ['except' => '']], $this->{$qsProperty});
        $this->{$qsProperty} = array_merge([$this->filterQueryString => ['except' => []]], $this->{$qsProperty});
        if($defaultOrder) $this->{$qsProperty} = array_merge([$this->orderQueryString => ['except' => $defaultOrder]], $this->{$qsProperty});
        $this->search   = request()->query($this->searchQueryString , $this->search);
        $this->filtered = request()->query($this->filterQueryString , $this->filtered);
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
        // debug($property, $value);
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
        $data[$this->orderQueryString]  = $this->order;
        return $data;
    }

}
