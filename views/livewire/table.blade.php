<div>
    <div class="row">
        <div class="col-lg-6">
            Info
        </div>
        <div class="col-lg-6 text-right d-flex">
            @if ($search !== false)
                <label class="d-flex ml-auto">
                    <span class="py-1 pr-2"> @lang('Search')</span>
                    <input type="text" wire:model='search' class="form-control form-control-sm">
                </label>
            @endif
             [filter]
        </div>
    </div>
    <div class="table-responsive">
        <table class="{{ $table->class }}" id="{{$table->id}}">
            <thead>
                {!! $table->getColumns()->render($table->getContext(),
                    function ($def, $column) {
                        if($column->sort) $def['wire:click'] = 'sortColumn("' . $column->name . '")';
                        return $def;
                    })
                !!}
            </thead>
            <tbody>
                {!! $table->getRows()->render($table->getContext()) !!}
            </tbody>
        </table>
    </div>
    @php( $links = $table->getLinks() )
    @if ($links)
        <div class="row">
            <div class="col-lg-6">
            </div>
            <div class="col-lg-6">
                {!! $links !!}
            </div>
        </div>
    @endif
</div>
