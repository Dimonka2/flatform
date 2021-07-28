<?php

use dimonka2\flatform\Form\Contracts\IElement;

// use dimonka2\flatform\State;

return [
    'livewire' => [
        // it is possible to disable all Livewire dependent stuff here
        'active' => true,
        'table_view' => 'flatform::livewire.table',
        // here it is possible to add a script code that will adjust page top
        // in order to offset scroll events by fixed header height
        // for example: 'top_offset_script' => '- $("#top-header").height()'
        'top_offset_script' => '',
    ],
    'assets' => [
        'select2'   => [
            'path'  => 'admin/select2/',
            'view'  => 'flatform::select2',
            'js'    => 'js/select2.full.min.js',
            'css'   => [
                'css/select2.min.css',
                'css/select2-bootstrap4.css',
            ]
        ],
        'datatable' => [
            'render' => 'flatform::datatable',
            'path' => 'datatable/',
            'css' => 'dataTables.bootstrap4.min.css',
            'js' => [
                'jquery.dataTables.min.js',
                'dataTables.bootstrap4.min.js',
            ],
        ],
        'summernote' => [
            'view' => 'flatform::summernote',
            'path' => 'summernote/',
            'css' => [
                'summernote.css',
                'summernote-bs4.css',
            ],
            'js' => [
                'summernote.min.js',
                'summernote-bs4.min.js',
            ],
        ],

        'datepicker' => 'flatform::datepicker',
        'bootstrap-select-css' => null,
        'bootstrap-select-js' => null,
        'bootstrap-select' => 'flatform::bootstrap-select',
    ],

    // change the blade directive here in case you already using "form" in your project
    'blade_directive' => 'form',


    'form' => [
        //  default tag type
        'default-type' => 'div',
        // active styles config paths delimited by comma with a priority left to right
        'style' => 'bootstrap',
        // name of the stacks for JS and CSS
        'css_stack' => 'css',
        'js_stack'  => 'js',

        // default column style
        'col' => 'col-md-6',

        'date_format_js' => 'dd.mm.yy',

        // inputs that require div.col and label
        'inputs' => [
            'text', 'password', 'number', 'textarea', 'summernote',
            'select', 'select2', 'file', 'date', 'bselect',
        ],
    ],

    // template definitions
    'bootstrap' => [
        'date' => ['+class' => 'datepicker'],
        'input' => ['template' => 'flatform::bootstrap.input', '+class' => 'form-control'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::bootstrap.dropdown'],
        'error-class' => ['+class' => 'is-invalid', ],
        'button' => ['+class' => 'btn'],
        'checkbox' => ['template' => 'flatform::bootstrap.checkbox',],
        'select2' => ['+class' => 'select2', '+style' => 'width:100%;'],
        'row' => ['type' => 'div', 'class' => 'row',],
        'tab-item' => ['type' => 'a',],
        'dd-item' => ['class' => 'dropdown-item', 'type' => 'link',
            'onLoaded' => function(IElement $element, array $def){
                if($element->active) $element->addClass('active');
            }],
        'dd-item-icon' => ['type' => 'i',],
        'dd-item-title' => ['type' => 'span',],
        'dd-divider' => ['type' => 'div', 'class' => 'dropdown-divider', ],
        'link-form' => ['type' => 'form', 'class' => '', 'template' => false,],
        'progress' => ['template' => 'flatform::bootstrap.progress-bar', ],
        'alert' => ['template' => 'flatform::bootstrap.alert', ] ,
        'breadcrumbs' => ['template' => 'flatform::bootstrap.breadcrumbs'],
        'bselect' => ['type' => 'select', '+class' => 'bselect'],
        'table-dropdown' => ['type' => 'dropdown', 'icon' => 'fas fa-ellipsis-v',
            'size' => 'sm', 'color' => 'clean btn-icon btn-icon-md', 'shadow' => true],
        'badge' => ['type' => 'span', 'class' => 'badge',
            'onLoaded' => function(IElement $element, array $def){
                if($element->getColor()) $element->addClass('badge-' . $element->getColor());
                if($element->getPill()) $element->addClass('badge-pill');
            }],
        'dropzone' => ['+class' => 'dropzone'],
        'summernote' => ['+class' => 'summernote'],
        'modal' => ['template' => 'flatform::bootstrap.modal', ],
        'modal-close-button' => ['type' => 'button', 'data-dismiss' => "modal", ],
    ],

    'metronic_templates' =>[
        'input' => ['template' => 'flatform::metronic.input',
            '+class' => ' form-control form-control-alt',
            'onLoaded' => function(IElement $element, array $def){
                if($element->error) $element->addClass('is-invalid');
            }],
        'checkbox' => ['template' => 'flatform::metronic.checkbox', '+class' => 'kt-checkbox'],
        'radio' => ['template' => 'flatform::metronic.radio', '+class' => 'kt-radio'],
        'table-checkbox' => ['type' => 'checkbox', '+class' => 'kt-checkbox--single'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::metronic.dropdown'],
        'tabs' => ['type' => 'div', 'template' => 'flatform::metronic.tab-navs'],
        'widget' => ['type' => 'card'],
        'card' => ['template' => 'flatform::metronic.card'],
        'progress' => ['template' => 'flatform::metronic.progress-bar', ],

        'dd-item' => ['+class' => 'dropdown-item kt-nav__link', 'template' => 'flatform::metronic.dd-item',],

        'dd-item-icon' => ['type' => 'i', 'class' => 'kt-nav__link-icon',],
        'dd-item-title' => ['type' => 'span', 'class' => 'kt-nav__link-text',],
        'form' => ['+class' => 'kt-form',],
        'badge' => ['type' => 'span', 'class' => 'kt-badge',
            'onLoaded' => function(IElement $element, array $def){
                if($element->getColor()) $element->addClass('kt-badge--' . $element->getColor());
                if($element->getSize()) $element->addClass('kt-badge--' . $element->getSize());
                if($element->getPill()) $element->addClass('kt-badge--pill');
                if($element->getInline()) $element->addClass('kt-badge--inline');
                if($element->getAttribute('bold')) $element->addClass('kt-badge--bold');
                if($element->getAttribute('rounded')) $element->addClass('kt-badge--rounded');
            }
        ],
        'media' => ['type' => 'span', 'class' => 'kt-media kt-margin-r-5 kt-margin-t-5',
            'onLoaded' => function(IElement $element, array $def){
                $a = $element->getAttribute('color');   if($a) $element->addClass('kt-media--' . $a);
                $a = $element->getAttribute('size');    if($a) $element->addClass('kt-media--' . $a);
                $a = $element->getAttribute('circle');  if($a) $element->addClass('kt-media--circle');
                $a = $element->getAttribute('title');
                if($a) {
                    $element->readItems([['span', 'text' => $a]]);
                    $element->removeAttribute('title');
                }
            }
        ],

        'tab-content' => ['template' => 'flatform::metronic.tab-content',],
        'checkbox-list' => ['type' => 'div', 'class' => 'kt-checkbox-list', ],

        'breadcrumbs' => ['template' => 'flatform::metronic.breadcrumbs'],

    ],
    'limitless' => [
        'sidebar' => ['type' => 'menu', 'template' => 'flatform::limitless.menu'],
        'menu-item' => ['badgeClass' => 'ml-auto align-self-center', 'badgeColor' => 'blue-400',],
        'badge' => ['type' => 'span', 'class' => 'badge',
            'onLoaded' => function(IElement $element, $def){
            if($element->getAttribute('rounded')) $element->addClass('rounded-circle');
            if($element->getColor()) $element->addClass('bg-' . $element->getColor());
            if($element->getPill()) $element->addClass('badge-pill');
        }],
        'breadcrumbs' => ['template' => 'flatform::limitless.breadcrumbs'],
        'widget' => ['type' => 'card'],
        'card' => ['template' => 'flatform::limitless.card'],
        'tabs' => ['type' => 'div', 'template' => 'flatform::limitless.tab-navs'],
        'tab-content' => ['template' => 'flatform::limitless.tab-content',],
        'table-dropdown' => ['type' => 'dropdown', 'icon' => "icon-menu9",
            'size' => 'sm', 'color' =>  'clean btn-icon btn-icon-md',],

    ],

    'one_templates' =>[
        'input' => ['template' => 'flatform::one.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::one.checkbox', '+class' => 'form-check'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::one.dropdown'],
        'dd-item-icon' => ['type' => 'i', '+class' => 'mr-2'],

        'tabs' => ['type' => 'div', 'class' => 'js-wizard-simple block',
            'template' => 'flatform::one.tab-navs'],
        'widget' => ['type' => 'card'],
        'card' => ['template' => 'flatform::one.card'],
        'breadcrumbs' => ['template' => 'flatform::one.breadcrumbs'],
        // templates
        'tab-content' => ['template' => 'flatform::one.tab-content',],
        'sidebar' => ['type' => 'menu', 'template' => 'flatform::one.sidebar'],
    ],

    'canvas' => [
        'tabs' => ['type' => 'div', 'template' => 'flatform::canvas.tab-navs'],
        'tab-content' => ['template' => 'flatform::canvas.tab-content',],
        'alert' => ['template' => 'flatform::canvas.alert', ] ,
    ],


    // add application specific actions here
    'actions' => [
        // javascript run actions variable
        'js-function' => 'ffactions',
    ],

    // add more flatform elements here
    'bindings' => [],
];
