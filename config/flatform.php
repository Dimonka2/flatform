<?php

use dimonka2\flatform\Form\Contracts\IElement;

// use dimonka2\flatform\State;

return [
    'livewire' => [
        'active' => false,
        'table_view' => 'flatform::livewire.table',
    ],
    'assets' =>[
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
        'style' => 'metronic_templates,bootstrap',
        // name of the stacks for JS and CSS
        'css_stack' => 'css',
        'js_stack'  => 'js',

        // default column style
        'col' => 'col-6',

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
        'dd-item' => ['class' => 'dropdown-item', 'type' => 'link', ],
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
        'input' => ['template' => 'flatform::metronic.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::metronic.checkbox', '+class' => 'kt-checkbox'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::metronic.dropdown'],
        'tabs' => ['type' => 'div', 'template' => 'flatform::metronic.tab-navs'],
        'widget' => ['template' => 'flatform::metronic.widget'],
        'dd-item' => ['+class' => 'dropdown-item kt-nav__link', 'template' => 'flatform::metronic.dd-item',],
        'dd-item-icon' => ['type' => 'i', 'class' => 'kt-nav__link-icon',],
        'dd-item-title' => ['type' => 'span', 'class' => 'kt-nav__link-text',],
        'form' => ['+class' => 'kt-form',],

        'tab-content' => ['template' => 'flatform::metronic.tab-content',],
        'checkbox-list' => ['type' => 'div', 'class' => 'kt-checkbox-list mt-5', ],

        'breadcrumbs' => ['template' => 'flatform::metronic.breadcrumbs'],
        'badge' => ['type' => 'span', 'class' => 'kt-badge',
            'onLoaded' => function(IElement $element, array $def){
                if($element->getColor()) $element->addClass('kt-badge--' . $element->getColor());
                if($element->getSize()) $element->addClass('kt-badge--' . $element->getSize());
                if($element->getPill()) $element->addClass('kt-badge--pill');
                if($element->getPill()) $element->addClass('kt-badge--inline');
            }
        ],

    ],

    'one_templates' =>[
        'input' => ['template' => 'flatform::one.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::one.checkbox', '+class' => 'form-check'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::one.dropdown'],
        'dd-item-icon' => ['type' => 'i', '+class' => 'mr-2'],

        'tabs' => ['type' => 'div', 'class' => 'js-wizard-simple block',
            'template' => 'flatform::one.tab-navs'],
        'widget' => ['template' => 'flatform::one.widget'],
        'breadcrumbs' => ['template' => 'flatform::one.breadcrumbs'],
        // templates
        'tab-content' => ['template' => 'flatform::one.tab-content',],
        'sidebar' => ['type' => 'menu', 'template' => 'flatform::one.sidebar'],
    ],

    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],
    // add application specific actions here
    'actions' => [
        // action controller middleware
        'middleware' => null,
        // class (invoke) or callable with parameters: actionName, request
        // resolver may return:
        // * class name
        // * array [class name, [params] ]
        'resolver' => null,
        // javascript run actions variable
        'js-function' => 'ffactions',
    ],
    'selects' => [
        // select controller middleware
        'middleware' => null,
        // class (invoke) or callable with parameters: selectName, request
        // resolver has to return:
        // * class object that implements IAJAXSelect
        'resolver' => null,
    ],

    // add more flatform elements here
    'bindings' => [],
];
