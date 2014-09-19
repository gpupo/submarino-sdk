<?php

return [
    [[
        'id'            => 1,
        'name'          => 'Test Express',
        'deliveryType'  => 'SHIPMENT',
        'sku'           =>[
            [
                'id'            => 2,
                'name'          => 'Normal',
                'description'   => 'Hello World!',
                'ean'           => ['0102999984123','0102999984124'],
            ],
            [
                'id'            => 3,
                'name'          => 'Plus',
                'description'   => 'Hello Moon!',
                'ean'           => ['0102999984125','0102999984126'],
            ],
        ],
        'manufacturer' => [
            'name'          => 'Foo',
            'model'         => 'Bar',
            'warrantyTime'  => 30,
        ],
        'nbm'   => [
            'number' => 1041011,
            'origin' => 2,
        ],
    ]],
];