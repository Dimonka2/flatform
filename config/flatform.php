<?php

// use dimonka2\flatform\State;

return [
    'states' => [

    ],
    'assets' =>[

    ],

    // change the blade directive here in case you already using "form" in your project
    'blade_directive' => 'form',

    // template definitions

    'templates' =>[
        'input' => ['template' => 'flatform::input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::checkbox',],
        'dropdown' => ['type' => 'div', 'template' => 'flatform::dropdown'],
        'button' => ['+class' => 'btn'],
        'row' => ['type' => 'div', 'class' => 'row',],
        'dd-item' => ['template' => 'flatform::dropdown-item'],
        'dd-item-icon' => ['type' => 'i', 'class' => 'kt-nav__link-icon',],
        'dd-item-title' => ['type' => 'span', 'class' => 'kt-nav__link-text',],
        'form' => ['+class' => 'kt-form',],
    ],

    //  default tag type
    'form' => [
        'default-type' => 'div',
    ],
    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],
    'views_directory' => __DIR__.'/../views',
];
