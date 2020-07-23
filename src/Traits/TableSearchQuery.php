<?php

namespace dimonka2\flatform\Traits;

trait TableSearchQuery
{
    protected $searchQueryString = 'search';
    protected $filterQueryString = 'filtered';

    public function initializeTableSearchQuery()
    {
        $this->updatesQueryString = array_merge([$this->searchQueryString => ['except' => '']], $this->updatesQueryString);
        $this->updatesQueryString = array_merge([$this->filterQueryString => ['except' => []]], $this->updatesQueryString);
        $this->search = request()->query($this->searchQueryString, $this->search);
        $this->filtered = request()->query($this->filterQueryString, $this->filtered);
    }

    public function __get($property)
    {
        if ($property == $this->searchQueryString) {
            return $this->search;
        }
        if ($property == $this->filterQueryString) {
            return $this->filtered;
        }
        return $this;
    }

    public function __set($property, $value)
    {
        // debug($property, $value);
        if ($property == $this->searchQueryString) {
            $this->search = $value;
        } elseif($property == $this->filterQueryString) {
            $this->filtered = $value;
        }
        return $this;
    }
}
