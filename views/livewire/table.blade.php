<div>
    {!! $host->getElement('top') !!}
    <div class="row">
        <div class="col-lg-6">
            {!! $host->getElement('info') !!}
        </div>
        <div class="col-lg-6">
            @if($table->hasSearch())
            <div class="pull-right d-flex">
                @if ($search !== false)
                    <label class="d-inline">
                        <span class="py-1 pr-2 d-inline"> @lang('flatform::table.search')</span>
                        <input type="text" wire:model.debounce{{ $searchDebounce ? ('.' . $searchDebounce . 'ms') : '' }}='search' class="form-control form-control-sm d-inline-block w-auto">
                    </label>
                @endif
                <div class="d-inline ml-2 mr-2 btn-group" wire:ignore >
                   @form([
                       ['dropdown', 'toggle', 'group', 'color' => 'outline-secondary', 'size' => 'sm', 'shadow',
                            'icon' => 'fa fa-filter',
                            'drop_form' => [
                                ['form', 'style' => 'min-width:320px;', 'class' => 'row p-3', [
                                    ['_text', 'text' => $host->getElement('filters')],
                                    ['col', 'md' => 6, '+class' => 'my-auto', [
                                        ['div', 'text' =>  __('flatform::table.show_entries'), 'class' => '']
                                    ]],
                                    ['select',
                                        'label' => false,
                                        'class' => 'align-middle',
                                        'selected' => $length,
                                        'list' => $host->getLengthOptions(),
                                        'col' => ['md' => 6],
                                        'wire:model' => 'length',
                                    ],
                                ]],
                            ]
                        ]
                   ])
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="{{$class}}">
        <table class="{{ $table->class }}" id="{{$table->id}}">
            @if (!$noHeader)
            <thead class="{{ $table->getTheadClass() }}">
                {!! $host->renderHeader($table) !!}
            </thead>
            @endif
            <tbody>
                {!! $table->getRows()->render($table->getContext()) !!}
            </tbody>
        </table>
    </div>
    @php( $links = $table->getLinks('flatform::livewire.paginator') )
    @if ($links)
        <div class="row">
            <div class="col-md-5">
                {!! $host->getElement('info2') !!}
            </div>
            <div class="col-md-7">
                <div class="pull-right">
                    {!! $links !!}
                </div>

            </div>
        </div>
    @endif
    {!! $host->getElement('bottom') !!}
</div>
