<?php

namespace dimonka2\flatform\Livewire;

use Livewire\Component;

// this component is not a real component,
// it suppose to decouple rows from table
// and allow single row selection without rerendering other rows
class TableRowComponent extends Component
{
    public $_id;
    protected $row;
    public function mount($row, $id)
    {
        $this->row = $row;
        $this->_id = $id;

    }

    public function render()
    {
        return $this->row;
    }
}
