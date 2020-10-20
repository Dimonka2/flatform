<?php

namespace dimonka2\flatform\Livewire;

use Livewire\Component;

class DropdownComponent extends Component
{
    public $input;
    public $selected = [1 => 'abc', 2 => 'cde'];
    public $choices = [];
    public $counter = 6;

    public function render()
    {
        if($this->input && !count($this->choices)) {
            $this->choices = [
                3 => 'Foo',
                4 => 'Bar',
                5 => 'FooBar',
                7 => 'FooBar2',
                8 => 'FooBar3',
            ];
        }
        return view('flatform::livewire.dropdown');
    }

    public function removeItem($id)
    {
        unset($this->selected[$id]);
    }

    public function addTag($input)
    {
        $this->selected[$this->counter++] = $input;
        $this->input = "";
    }

    public function addChoice($idx)
    {
        debug($idx);
        if($this->choices[$idx] ?? false) {
            $this->selected[$idx] = $this->choices[$idx];
            $this->input = "";
        }
    }
}
