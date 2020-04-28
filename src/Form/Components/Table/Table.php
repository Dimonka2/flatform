<?php
namespace dimonka2\flatform\Form\Components\Table;

use dimonka2\flatform\Form\ElementContainer;

class Table extends ElementContainer
{
    use ColumnsTrait;
    protected $order;
    protected $start;
    protected $length;
    protected $rows;

    protected $count;

    // use FiltersTrait;

    protected function read(array $element)
    {
        $columns = self::readSingleSetting($element, 'columns');
        $this->createColumns($columns ?? []);

//        $filters = self::readSingleSetting($element, 'filters');
//        $this->createFilters($filters ?? []);

//        $details = self::readSingleSetting($element, 'details');
//        if(is_array($details)) $this->createDetails($details);

//        $select = self::readSingleSetting($element, 'select');
//        if(is_array($select)) $this->createSelect($select);

        $this->readSettings($element, [
            'order',
            'start',
            'length',
        ]);

        parent::read($element);
        $this->requireID();
    }
}
