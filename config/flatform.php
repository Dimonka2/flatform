<?php

// use dimonka2\flatform\State;

/*
    _surround - add outer element
    _elements - add sub elements
    _text     - add html text element
    _label    - add related label element
*/

return [
    'states' => [

    ],
    'assets' =>[
        
    ],
    'templates' =>[
        'input' => ['template' => 'flatform::input', '+class' => ' form-control form-control-alt'],
        'checkbox' => ['template' => 'flatform::checkbox',],
    ],
    'form' => [
        'default-type' => 'div',
    ],
    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],
];
