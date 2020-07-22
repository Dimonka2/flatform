<?php

namespace dimonka2\flatform\Traits;

use Illuminate\Pagination\Paginator;

trait WithPagination
{
    protected $pageQueryString = 'page';

    public function initializeWithPagination()
    {
        $this->updatesQueryString = array_merge([$this->pageQueryString => ['except' => 1]], $this->updatesQueryString);
        $this->{$this->pageQueryString} = $this->resolvePage();

        Paginator::currentPageResolver(function () {
            return $this->{$this->pageQueryString};
        });

        Paginator::defaultView($this->paginationView());
    }

    public function paginationView()
    {
        return 'livewire::pagination-links';
    }

    public function previousPage()
    {
        $this->{$this->pageQueryString} = $this->{$this->pageQueryString} - 1;
    }

    public function nextPage()
    {
        $this->{$this->pageQueryString} = $this->{$this->pageQueryString} + 1;
    }

    public function gotoPage($page)
    {
        $this->{$this->pageQueryString} = $page;
    }

    public function resetPage()
    {
        $this->{$this->pageQueryString} = 1;
    }

    public function resolvePage()
    {
        // The "page" query string item should only be available
        // from within the original component mount run.
        return request()->query($this->pageQueryString, $this->{$this->pageQueryString});
    }

    public function __get($property)
    {
        if ($property == $this->pageQueryString || $property == 'page') {
            $page = property_exists($this, $this->pageQueryString) ? $this->{$this->pageQueryString} : 1;
            return $page;
        }
      }

      public function __set($property, $value)
      {
        if ($property == $this->pageQueryString || $property == 'page') {
            $this->{$this->pageQueryString} = $value;
        }
        return $this;
      }

      protected function addPaginatorPublicPropertiesDefinedBySubClass($data)
      {
          $data[$this->pageQueryString] = $this->{$this->pageQueryString};
          return $data;
      }
}
