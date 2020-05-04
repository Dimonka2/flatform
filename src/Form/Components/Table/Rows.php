<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\Contracts\IContext;

class Rows implements \ArrayAccess, \Countable, \IteratorAggregate
{
    use ItemsTrait;
    protected $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->items = collect();
    }

    public function render(IContext $context)
    {
        $out = '';
        foreach($this->items as $item){
            $cols = [];
            foreach ($this->table->getColumns() as $col) {
                $cols[] = ['td', 'text' => $item[$col->name]];
            }
            $def = ['tr', $cols];
            $out .= $this->table->renderItem([$def]);
        }
        return $out;
    }


}
