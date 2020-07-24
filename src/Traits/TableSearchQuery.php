<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString    = 'search';
    protected $filterQueryString    = 'filtered';
    protected $orderQueryString     = 'order';

    public function initializeTableSearchQuery()
    {
        $this->updatesQueryString = array_merge([$this->searchQueryString => ['except' => '']], $this->updatesQueryString);
        $this->updatesQueryString = array_merge([$this->filterQueryString => ['except' => []]], $this->updatesQueryString);
        $this->updatesQueryString = array_merge([$this->orderQueryString => ['except' => $this->defaultOrder]], $this->updatesQueryString);
        $this->search   = request()->query($this->searchQueryString , $this->search);
        $this->filtered = request()->query($this->filterQueryString , $this->filtered);
        $this->order    = request()->query($this->orderQueryString  , $this->defaultOrder);
    }

    public function __get($property)
    {
        debug($property);
        debug($this->order);

        if ($property == $this->searchQueryString) {
            return $this->search;
        } elseif ($property == $this->filterQueryString) {
            return $this->filtered;
        }elseif ($property == $this->orderQueryString) {
            return $this->order;
        }
    }

    public function __set($property, $value)
    {
        // debug($property, $value);
        if ($property == $this->searchQueryString) {
            $this->search = $value;
        } elseif($property == $this->filterQueryString) {
            $this->filtered = $value;
        } elseif($property == $this->orderQueryString) {
            $this->order = $value;
        }
        return $this;
    }

    protected function addSearchPublicPropertiesDefinedBySubClass($data)
    {
        $data[$this->searchQueryString] = $this->search;
        $data[$this->filterQueryString] = $this->filtered;
        $data[$this->orderQueryString]  = $this->order;
        return $data;
    }

}
