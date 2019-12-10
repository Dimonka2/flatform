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
    'form' => [
        'default-type' => 'div',
        'checkbox' => [
            '_surround' =>
                ['type' => 'div', 'class' => 'custom-control custom-checkbox mb-3',
                    '_surround' => ['type' => 'div', 'class' => 'form-group', ]
                ],
            'label-class' => 'form-check-label custom-control-label',
        ],
    ],
    'aliases' => [
        'Form'  => Collective\Html\FormFacade::class,
        'HTML'  => Collective\Html\HtmlFacade::class,
    ],
];
