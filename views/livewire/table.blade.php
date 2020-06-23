<div>
    <div class="row">
        <div class="col-lg-6">
            {{ $host->getInfo() }}
        </div>
        <div class="col-lg-6">
            <div class="pull-right d-flex">
                @if ($search !== false)
                    <label class="d-inline">
                        <span class="py-1 pr-2 d-inline"> @lang('Search')</span>
                        <input type="text" wire:model.debounce{{ $searchDebounce ? ('.' . $searchDebounce . 'ms') : '' }}='search' class="form-control form-control-sm d-inline-block w-auto">
                    </label>
                @endif
                <div class="d-inline ml-2 mr-2 btn-group">
                   @form([
                       ['dropdown', 'toggle', 'group', 'color' => 'outline-secondary', 'size' => 'sm', 'shadow',
                            'icon' => 'fa fa-filter',
                            'drop_form' => [
                                ['form', 'style' => 'min-width:320px;', 'class' => 'p-3', [
                                    ['div', 'class' => 'row', [
                                        ['_text', 'text' => $host->renderFilters()],
                                        ['col', 'md' => 6, '+class' => 'p-3', 'text' =>  __('Show entries'), ],
                                        ['select',
                                            'label' => false,
                                            'selected' => $length,
                                            'list' => $host->getLengthOptions(),
                                            'col' => ['md' => 6],
                                            'wire:model' => 'length',
                                        ],
                                    ]],
                                ]],
                            ]
                        ]
                   ])
                </div>
            </div>

        </div>
    </div>
    <div class="{{$class}}">
        <table class="{{ $table->class }}" id="{{$table->id}}">
            <thead>
                {!! $host->renderHeader($table) !!}
            </thead>
            <tbody>
                {!! $table->getRows()->render($table->getContext()) !!}
            </tbody>
        </table>
    </div>
    @php( $links = $table->getLinks('flatform::livewire.paginator') )
    @if ($links)
        <div class="row">
            <div class="col-md-5">
            </div>
            <div class="col-md-7">
                <div class="pull-right">
                    {!! $links !!}
                </div>

            </div>
        </div>
    @endif
</div>
