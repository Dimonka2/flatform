<?php

namespace dimonka2\flatform\Helpers;
use Illuminate\Http\Request;
use dimonka2\flatform\Form\Components\Datatable;
use DB;
use dimonka2\flatform\Form\Components\DatatableDetails;

class DatatableAjax
{
    private static function addSearch($query, $term, Datatable $table)
    {
        $tablesearch = '%' . $term . '%';
        $query = $query->where(function($q) use ($tablesearch, $table) {
            foreach($table->getColDefinition() as $field) {
                if ($field->search) {
                    $q = $q->orWhere( $field->name, 'like', $tablesearch );
                }
            }
        });

        return $query;
    }

    public static function processDetails(Request $request, Datatable $table)
    {
        if($table->hasDetails() ) {
            $details = $table->getDetails();
            if($details->hasFormatter()) return $details->format($request);
        }
        return response()->json(['error' => 'Table has no details formatter!'], 400);
    }

    public static function process(Request $request, Datatable $table, $query)
    {
        // built in details!!
        if($request->has(DatatableDetails::ajax_parameter)) {
            return self::processDetails($request, $table);
        }

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
        $fields = $table->getColDefinition();


        if ($request->has('order.0.column')) {
            $orderColumn = $fields[$request->input('order.0.column') - ($table->hasDetails() ? 1 : 0)];
            // protect from errors
            if($orderColumn->getSort() !== false && !$orderColumn->getSystem()) {
                $orderDir = $request->input('order.0.dir');
                if(!$table->getNullLast()) {
                    if($orderDir) {
                        $query = $query->orderBy($orderColumn->name, $orderDir);
                    } else {
                        $query = $query->orderBy($orderColumn->name);
                    }
                } else {
                    if($orderDir) {
                        $query = $query->orderByRaw($orderColumn->name . ' ' . $orderDir . ' NULLS LAST');
                    } else {
                        $query = $query->orderByRaw($orderColumn->name . ' NULLS LAST');
                    }
                }
            }
        }
        // add select
        $fields = [];
        foreach($table->getColDefinition() as $field) {
            if (!$field->noSelect && !$field->system) {
                $fieldName = $field->name . ($field->as ? ' as ' . $field->as : '' );
                $fields[] = $fieldName;
                // $query = $query->addSelect($fieldName);
            }
        }
        $query = $query->addSelect($fields);
        $items = $query->get();
        if (\App::environment('local')) {
            debug($query->toSql() );
            debug($items, $table);
        }
        $data = [];
        foreach ($items as $item) {
            $nestedData = [];
            foreach($table->getColDefinition() as $column) {
                $value = '';
                if (!$column->system) {
                    $value = $item->{$column->as ? $column->as : $column->name};
                }
                $field = $column->getSafeName();
                if($column->hasFormatter()) {
                    $nestedData[ $field ] = $column->format($value, $item);
                } else if($table->hasFormatter()) {
                    $nestedData[ $field ] = $table->format($value, $item, $column);
                } else  $nestedData[ $field ] = $value;
            }
            if($table->hasDetails()) $nestedData['DT_RowId'] =
                $table->id . '-' . $nestedData[$table->getDetails()->data_id];
            $data[] = $nestedData;
        }
        // $this->formatJSON($items, $table->getColDefinition());
        $json_data = [
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            ];

        return response()->json($json_data);

    }

}
