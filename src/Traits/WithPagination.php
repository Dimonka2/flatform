<?php

namespace dimonka2\flatform\Traits;

use Illuminate\Pagination\Paginator;

trait WithPagination
{
    public $page = 1;
    protected $pageQueryString = 'page';

    public function initializeWithPagination()
    {
        $this->updatesQueryString = array_merge([$this->pageQueryString => ['except' => 1]], $this->updatesQueryString);
        $this->page = $this->resolvePage();

        Paginator::currentPageResolver(function () {
            return $this->page;
        });

        Paginator::defaultView($this->paginationView());
    }

    public function paginationView()
    {
        return 'livewire::pagination-links';
    }

    public function previousPage()
    {
        $this->page += -1;
        $this->afterPageChange();
    }

    public function nextPage()
    {
        $this->page += 1;
        $this->afterPageChange();
    }

    public function gotoPage($page)
    {
        $this->page = $page;
        $this->afterPageChange();
    }

    public function resetPage()
    {
        $this->page = 1;
        $this->afterPageChange();
    }

    public function resolvePage()
    {
        // The "page" query string item should only be available
        // from within the original component mount run.
        return request()->query($this->pageQueryString, $this->page);
    }

    protected function paginator__get($property)
    {
        if ($property == $this->pageQueryString) {
            return [$this->page];
        }
      }

      protected function paginator__set($property, $value)
      {
        if ($property == $this->pageQueryString) {
            $this->page = $value;
            return $this;
        }
      }

      protected function addPaginatorPublicPropertiesDefinedBySubClass($data)
      {
          $data[$this->pageQueryString] = $this->page;
          return $data;
      }
}
