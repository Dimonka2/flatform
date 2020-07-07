<?php
namespace dimonka2\flatform\Form\Components\Table;

use Closure;
use dimonka2\flatform\Flatform;
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
        $table = $this->table;
        $tableFormat = $table->getFormatFunction();
        $rowRenderCallback = $table->getRowRenderCallback();
        $evenOdd = $table->getEvenOddClasses();
        $details = $table->hasDetails() ? $table->getDetails() : 0;
        $select = $table->hasSelect() ? $table->getSelect() : 0;
        $i = 0;
        if($this->count() == 0) {
            // return "no data" row
            $label = ['row', [['col', 'col' => 12, 'class' => 'd-flex', [['div', 'class' => 'mx-auto', 'text' => __('flatform::table.no_data')]]]]];
            $td = ['td', 'colspan' => $this->table->getVisibleColumnCount(), [$label]];
            $tr = ['tr', [$td]];
            $html = Flatform::render([$tr]);
            if($rowRenderCallback instanceof Closure) $html = $rowRenderCallback(null, $html);
            return $html;
        }

        foreach($this->items as $row){
            $columns = [];

            if($select){
                $td = ['td', [$select->getCheckbox()]];
                if($select->width) $td['style'] = 'width:' . $select->width . ';';
                $columns[] = $td;
            }

            if($details){
                $td = ['td', [$details->getExpander()]];
                if($details->width) $td['style'] = 'width:' . $details->width . ';';
                if($details->class) $td['class'] = $details->class;
                $columns[] = $td;
            }
            foreach ($table->getColumns() as $column) {
                if($column->visible()) {
                    $val = $row[$column->name];
                    if(is_array($val)) {
                        $td = ['td', 'items' => $val];
                    } else {
                        if($column->hasFormat()) {
                            $val = $column->doFormat($val, $row);
                        }
                        if($tableFormat instanceof Closure) {
                            $val = $tableFormat($column->name, $val, $row);
                        }
                        $td = ['td', 'text' => $val];
                    }
                    if($column->class) $td['class'] = $column->class;
                    $columns[] = $td;
                }
                        if($column->width) $td['style'] = 'width:' . $column->width . ';';
        }
            $def = ['tr',  $columns, 'class' => ''];
            if(is_array($evenOdd)) $def['class'] .= ' ' . $evenOdd[$i % 2];
            if($select && $row->_selected && $select->class) $def['class'] .= ' ' . $select->class;

            $this->row = $row;
            $context->setDataProvider($this);
            $row_html = $table->renderItem([$def]);
            $context->setDataProvider(null);

            if($rowRenderCallback instanceof Closure) $row_html = $rowRenderCallback($row, $row_html);
            $html .= $row_html;
            if($table->hasDetails() && $row->_expanded){
                $row_html = $details->render($row);
                if($rowRenderCallback instanceof Closure) $row_html = $rowRenderCallback($row, $row_html, true);
                $html .= $row_html;
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
