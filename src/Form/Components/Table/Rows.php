<?php
namespace dimonka2\flatform\Form\Components\Table;

use Closure;
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
        $tableFormat = $this->table->getFormatFunction();
        foreach($this->items as $item){
            $columns = [];
            foreach ($this->table->getColumns() as $column) {
                if($column->visible()) {
                    $val = $item[$column->name];
                    if(is_array($val)) {
                        $td = ['td', 'items' => $val];
                    } else {
                        if($column->hasFormat()) {
                            $val = $column->doFormat($val, $item);
                            // debug($val);
                        }
                        if($tableFormat instanceof Closure) {
                            $val = $tableFormat($column->name, $val, $item);
                        }
                        $td = ['td', 'text' => $val];
                    }
                    if($column->class) $td['class'] = $column->class;
                    $columns[] = $td;
                }
            }
            $def = ['tr', $columns];
            $out .= $this->table->renderItem([$def]);
        }
        return $out;
    }


}
