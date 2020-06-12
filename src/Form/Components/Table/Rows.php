<?php
namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Form\Contracts\IContext;
use dimonka2\flatform\Form\Contracts\IDataProvider;

class Rows implements \ArrayAccess, \Countable, \IteratorAggregate, IDataProvider
{
    use ItemsTrait;
    protected $table;
    protected $row;

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->items = collect();
    }

    public function render(IContext $context)
    {
        $html = '';
        $tableFormat = $this->table->getFormatFunction();
        $rowRenderCallback = $this->table->getRowRenderCallback();
        $evenOdd = $this->table->getEvenOddClasses();
        $details = $this->table->getDetails();
        $i = 0;
        foreach($this->items as $row){
            $columns = [];
            if($this->table->hasDetails()){
                $td = ['td', [$details->getExpander()]];
                if($details->class) $td['class'] = $details->class;
                $columns[] = $td;
            }
            foreach ($this->table->getColumns() as $column) {
                if($column->visible()) {
                    $val = $row[$column->name];
                    if(is_array($val)) {
                        $td = ['td', 'items' => $val];
                    } else {
                        if($column->hasFormat()) {
                            $val = $column->doFormat($val, $row);
                            // debug($val);
                        }
                        if($tableFormat instanceof Closure) {
                            $val = $tableFormat($column->name, $val, $row);
                        }
                        $td = ['td', 'text' => $val];
                    }
                    if($column->class) $td['class'] = $column->class;
                    $columns[] = $td;
                }
            }
            $def = ['tr',  $columns];
            if(is_array($evenOdd)) $def['class'] = $evenOdd[$i % 2];
            $this->row = $row;
            $context->setDataProvider($this);
            $html .= $this->table->renderItem([$def]);
            $context->setDataProvider(null);
            if($rowRenderCallback instanceof Closure) $html = $rowRenderCallback($row, $html);
            if($this->table->hasDetails() && $row->_expanded){
                $html .= $details->render($row);
                if($rowRenderCallback instanceof Closure) $html = $rowRenderCallback($row, $html, true);
            }
            $i++;
        }
        return $html;
    }

    public function getData($name)
    {
        if(!$this->row) return;
        if($name == '_item') return $this->row;
        return $this->row->{$name} ?? null;
    }

}
