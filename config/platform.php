<?php

// use dimonka2\platform\State;

return [
    'states' => [

    ],

    'form' => [
        'checkbox' => [
            '_surround' =>
                ['type' => 'div', 'class' => 'custom-control custom-checkbox mb-3',
                    '_surround' => ['type' => 'div', 'class' => 'form-group', ]
                ],
            'label-class' => 'form-check-label custom-control-label',
        ],
    ]
];
