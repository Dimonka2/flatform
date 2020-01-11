<?php

// use dimonka2\flatform\State;

return [
    'assets' =>[
        'select2' => 'flatform::select2',
        'datatable' => 'flatform::datatable',
        'datatable_path' => 'datatable/',
        'datatable_css' => 'dataTables.bootstrap4.min.css',
        'datatable_js' => [
            'jquery.dataTables.min.js',
            'dataTables.bootstrap4.min.js',
        ],
        'summernote_path' => 'summernote/',
        'summernote_css' => [
            'summernote.css',
            'summernote-bs4.css',
        ],
        'summernote_js' => [
            'summernote.min.js',
            'summernote-bs4.min.js',
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

        // inputs that require div.col and label
        'inputs' => [
            'text', 'password', 'number', 'textarea', 'summernote',
            'select', 'select2', 'file', 'date', 'bselect',
        ],
    ],

    // template definitions
    'bootstrap' => [
        'error-class' => ['+class' => 'is-invalid', ],
        'button' => ['+class' => 'btn'],
        'select2' => ['+class' => 'select2', '+style' => 'width:100%;'],
        'row' => ['type' => 'div', 'class' => 'row',],
        'tab-item' => ['type' => 'a',],
        'dd-item' => ['class' => 'dropdown-item', 'type' => 'a', ],
        'dd-item-icon' => ['type' => 'i',],
        'dd-item-title' => ['type' => 'span',],
        'link-form' => ['type' => 'form', 'class' => '', 'template' => false,],
        'progress' => ['template' => 'flatform::bootstrap.progress-bar', ],
        'alert' => ['template' => 'flatform::bootstrap.alert', ] ,
        'breadcrumbs' => ['template' => 'flatform::bootstrap.breadcrumbs'],
        'bselect' => ['type' => 'select', '+class' => 'bselect'],
    ],

    'metronic_templates' =>[
        'input' => ['template' => 'flatform::metronic.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::metronic.checkbox', '+class' => 'kt-checkbox'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::metronic.dropdown'],
        'tabs' => ['type' => 'div', 'template' => 'flatform::metronic.tab-navs'],
        'widget' => ['template' => 'flatform::metronic.widget'],
        // templates
        'dd-item' => ['class' => 'dropdown-item kt-nav__link', 'template' => 'flatform::metronic.dd-item', ],
        'dd-item-icon' => ['type' => 'i', 'class' => 'kt-nav__link-icon',],
        'dd-item-title' => ['type' => 'span', 'class' => 'kt-nav__link-text',],
        'form' => ['+class' => 'kt-form',],

        'tab-content' => ['template' => 'flatform::metronic.tab-content',],
        'checkbox-list' => ['type' => 'div', 'class' => 'kt-checkbox-list mt-5', ],

        'breadcrumbs' => ['template' => 'flatform::metronic.breadcrumbs'],

    ],

    'one_templates' =>[
        'input' => ['template' => 'flatform::one.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::one.checkbox', '+class' => 'form-check'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::one.dropdown'],

        'tabs' => ['type' => 'div', 'class' => 'js-wizard-simple block',
            'template' => 'flatform::one.tab-navs'],
        'widget' => ['template' => 'flatform::one.widget'],
        'breadcrumbs' => ['template' => 'flatform::one.breadcrumbs'],
        // templates
        'tab-content' => ['template' => 'flatform::one.tab-content',],
    ],

    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],

    'bindings' => [
        // inputs
        'text' => dimonka2\flatform\Form\Inputs\Text::class,
        'password' => dimonka2\flatform\Form\Inputs\Password::class,
        'number' => dimonka2\flatform\Form\Inputs\Number::class,
        'textarea' => dimonka2\flatform\Form\Inputs\Textarea::class,
        'summernote' => dimonka2\flatform\Form\Inputs\Summernote::class,
        'select' => dimonka2\flatform\Form\Inputs\Select::class,
        'select2' => dimonka2\flatform\Form\Inputs\Select2::class,
        'bselect' => dimonka2\flatform\Form\Inputs\BootstrapSelect::class,
        'file' => dimonka2\flatform\Form\Inputs\File::class,
        'checkbox' => dimonka2\flatform\Form\Inputs\Checkbox::class,
        'radio' => dimonka2\flatform\Form\Inputs\Radio::class,
        'date' => dimonka2\flatform\Form\Inputs\Date::class,
        'hidden' => dimonka2\flatform\Form\Inputs\Hidden::class,

        // components
        'tabs' => dimonka2\flatform\Form\Components\Tabs::class,
        'widget' => dimonka2\flatform\Form\Components\Widget::class,
        'dropdown' => dimonka2\flatform\Form\Components\Dropdown::class,
        'dd-item' => dimonka2\flatform\Form\Components\DropdownItem::class,
        'datatable' => dimonka2\flatform\Form\Components\Datatable::class,
        'breadcrumbs' => dimonka2\flatform\Form\Components\Breadcrumbs::class,
        'progress' => dimonka2\flatform\Form\Components\Progress::class,
        'alert' => dimonka2\flatform\Form\Components\Alert::class,

        // links and buttons
        'a' => dimonka2\flatform\Form\Link::class,
        'submit' => dimonka2\flatform\Form\Components\Button::class,
        'button' => dimonka2\flatform\Form\Components\Button::class,

        'form' => dimonka2\flatform\Form\Form::class,

        'div' => dimonka2\flatform\Form\ElementContainer::class,
        'span' => dimonka2\flatform\Form\ElementContainer::class,
        'col' => dimonka2\flatform\Form\Column::class,
        'i' => dimonka2\flatform\Form\ElementContainer::class,
        'b' => dimonka2\flatform\Form\ElementContainer::class,
        'u' => dimonka2\flatform\Form\ElementContainer::class,
        'ul' => dimonka2\flatform\Form\ElementContainer::class,
        'li' => dimonka2\flatform\Form\ElementContainer::class,
        'label' => dimonka2\flatform\Form\Label::class,
        '_text' => dimonka2\flatform\Form\Element::class,
        '_template' => dimonka2\flatform\Form\Element::class,
        'option' => dimonka2\flatform\Form\Element::class,

        // blade directives
        'include'   => dimonka2\flatform\Form\BladeDirective::class,
        'stack'     => dimonka2\flatform\Form\BladeDirective::class,
        'yield'     => dimonka2\flatform\Form\BladeDirective::class,
        'extends'     => dimonka2\flatform\Form\BladeDirective::class,
        'section'     => dimonka2\flatform\Form\BladeDirective::class,
    ],
];
