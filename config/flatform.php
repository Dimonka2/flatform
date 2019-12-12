<?php

// use dimonka2\flatform\State;

return [
    'states' => [

    ],
    'assets' =>[
        'select2' => 'flatform::select2',
    ],

    // change the blade directive here in case you already using "form" in your project
    'blade_directive' => 'form',
    
    // template definitions

    'metronic_templates' =>[
        'input' => ['template' => 'flatform::metronic.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::metronic.checkbox', '+class' => 'kt-checkbox'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::metronic.dropdown'],
        'button' => ['+class' => 'btn'],
        'select2' => ['+class' => 'select2', '+style' => 'width:100%;'],
        'row' => ['type' => 'div', 'class' => 'row',],
        // templates
        'link-form' => ['type' => 'form', 'class' => '', 'template' => false,],
        'dd-item' => ['class' => 'dropdown-item kt-nav__link', 'template' => 'flatform::metronic.dd-item', ],
        'dd-item-icon' => ['type' => 'i', 'class' => 'kt-nav__link-icon',],
        'dd-item-title' => ['type' => 'span', 'class' => 'kt-nav__link-text',],
        'form' => ['+class' => 'kt-form',],
    ],

    'one_templates' =>[
        'input' => ['template' => 'flatform::one.input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::one.checkbox', '+class' => 'kt-checkbox'],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::one.dropdown'],
        'button' => ['+class' => 'btn'],
        'select2' => ['+class' => 'select2', '+style' => 'width:100%;'],
        'row' => ['type' => 'div', 'class' => 'row',],
        'tabs' => ['type' => 'div', 'class' => 'js-wizard-simple block', 
            'template' => 'flatform::one.tab-navs'],

        // templates
        'link-form' => ['type' => 'form', 'class' => '', 'template' => false,],
        'dd-item' => ['class' => 'dropdown-item', 'type' => 'a', ],
        'dd-item-icon' => ['type' => 'i',],
        'dd-item-title' => ['type' => 'span',],
        'tab-item' => ['type' => 'a',],
        'tab-content' => ['template' => 'flatform::one.tab-content',],
    ],

    //  default tag type
    'form' => [
        'default-type' => 'div',
        'style' => 'one_templates', 
    ],
    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],
];
