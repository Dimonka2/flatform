<?php

namespace dimonka2\flatform\Traits;
use Illuminate\Http\Request;
use dimonka2\flatform\Form\Bootstrap\Components\Datatable\Datatable;
use DB;

trait DatatableAjaxTrait
{

    public function addFieldAs($options)
    {
        foreach($options['fields'] as $key => $field) {
            if (empty($field['as'])) {
                $name = $field['name'];
                $as = str_replace('.', '__', $name);
                if ($as != $name){
                    $options['fields'][$key]['as'] = $as;
                }
            }
        }
        return $options;
    }

    private static function addSearch($query, $term, $options)
    {
        $tablesearch = '%' . $term . '%';
        $query = $query->where(function($q) use ($tablesearch, $options) {
            foreach($options['fields'] as $field) {
                if (self::isTrue($field, 'search')) {
                    $q = $q->orWhere( $field['name'], 'like', $tablesearch );
                }
            }
        });

        return $query;

    }

    private static function isTrue(array $arr, $index)
    {
        return isset($arr[$index]) && $arr[$index];
    }

    public function processDatableAjax(Request $request, $options, $query)
    {
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $tablesearch = strtolower( $request->input('search.value'));
        if ( $tablesearch != '') {
            $query = self::addSearch($query, $tablesearch, $options);
            $totalFiltered = $query->count();
        }
        if ($request->has('length') ) {
            $limit = $request->length;
        } else {
            $limit = 10;
        }
        $start = $request->input('start');
        $query = $query->limit($limit)->offset($start);
        $fields = collect($options['fields']);


        if ($request->has('order.0.column')) {
            $orderColumn = $options['fields'][$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');
            if(!self::isTrue($options, 'nulls_last')) {
                $query = $query->orderBy($orderColumn['name'], $orderDir);
            } else {
                $query = $query->orderByRaw($orderColumn['name'] . ' ' . $orderDir . ' NULLS LAST');
            }
        }
        // add select
        $fields = [];
        foreach($options['fields'] as $field) {
            if (!self::isTrue($field, 'noSelect') && !self::isTrue($field, 'system')) {
                $fieldName = $field['name'] . (isset($field['as']) ? ' as ' . $field['as'] : '' );
                $fields[] = $fieldName;
                // $query = $query->addSelect($fieldName);
            }
        }
        $query = $query->addSelect($fields);
        $items = $query->get();
        if (\App::environment('local')) {
            debug($query->toSql() );
            debug($items, $options);
        }
        $data = $this->formatJSON($items, $options['fields']);
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

       echo json_encode($json_data);

    }

    public function processDatableAjax2(Request $request, Datatable $table, $query)
    {
        $totalData = $query->count();
        $totalFiltered = $totalData;
        $tablesearch = strtolower( $request->input('search.value'));
        if ( $tablesearch != '') {
            $query = self::addSearch($query, $tablesearch, $table);
            $totalFiltered = $query->count();
        }
        if ($request->has('length') ) {
            $limit = $request->length;
        } else {
            $limit = 10;
        }
        $start = $request->input('start');
        $query = $query->limit($limit)->offset($start);
        $fields = $table->getColumns();


        if ($request->has('order.0.column')) {
            $orderColumn = $fields[$request->input('order.0.column')];
            $orderDir = $request->input('order.0.dir');
            if(!$table->getNullLast()) {
                $query = $query->orderBy($orderColumn->name, $orderDir);
            } else {
                $query = $query->orderByRaw($orderColumn->name . ' ' . $orderDir . ' NULLS LAST');
            }
        }
        // add select
        $fields = [];
        foreach($table->getColumns() as $field) {
            if (!$field->noSelect && !$field->system) {
                $fieldName = $field->name . ($field->as ? ' as ' . $field->as : '' );
                $fields[] = $fieldName;
                // $query = $query->addSelect($fieldName);
            }
        }
        $query = $query->addSelect($fields);
        $items = $query->get();
        if ($table->getDebug() && \App::environment('local')) {
            debug($query->toSql() );
            debug($items, $table);
        }
        $data = [];
        foreach ($items as $item) {
            $nestedData = [];
            foreach($table->getColumns() as $column) {
                $value = '';
                $field = $column->as ? $column->as : $column->name;
                if (!$column->system) {
                    $value = $item->{$field};
                }
                if($column->hasFormatter()) {
                    $nestedData[ $field ] = $column->format($value, $item);
                } else if($table->hasFormatter()) {
                    $nestedData[ $field ] = $table->format($value, $item, $column);
                } else  $nestedData[ $field ] = $value;
            }
            $data[] = $nestedData;
        }
        // $this->formatJSON($items, $table->getColumns());
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

       echo json_encode($json_data);

    }

    private function formatJSON($items, $fields)
    {
        $data = [];
        if(!empty($items))
        {
            foreach ($items as $item)
            {
                $nestedData = [];
                foreach($fields as $column) {
                    $value = '';
                    $field = (isset($column['as']) ? $column['as'] : $column['name']);
                    if (empty($column['system'])) {
                        $value = $item->{$field};
                    }
                    $nestedData[ $field ] = $this->formatColumn($column['name'], $value, $item);
                }
                $data[] = $nestedData;
            }
        }
        return $data;
    }

}
