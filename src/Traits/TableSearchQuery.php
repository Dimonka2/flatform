<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString    = 'search';
    protected $filterQueryString    = 'filtered';
    protected $orderQueryString     = 'sort';

    public function initializeTableSearchQuery()
    {
        $table = $this->getTable();
        $defaultOrder = $table->setOrder($table->getOrder())->getOrder();

        $this->updatesQueryString = array_merge([$this->searchQueryString => ['except' => '']], $this->updatesQueryString);
        $this->updatesQueryString = array_merge([$this->filterQueryString => ['except' => []]], $this->updatesQueryString);
        $this->updatesQueryString = array_merge([$this->orderQueryString => ['except' => $defaultOrder]], $this->updatesQueryString);
        $this->search   = request()->query($this->searchQueryString , $this->search);
        $this->filtered = request()->query($this->filterQueryString , $this->filtered);
        $this->order    = request()->query($this->orderQueryString  , $defaultOrder);
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

    protected function addSearchPublicPropertiesDefinedBySubClass($data)
    {
        $data[$this->searchQueryString] = $this->search;
        $data[$this->filterQueryString] = $this->filtered;
        $data[$this->orderQueryString]  = $this->order;
        return $data;
    }

}
