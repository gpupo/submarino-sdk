<?php

$generateEAN = function ($number) {
    $code = '200' . str_pad($number, 9, '0');
    $weightflag = true;
    $sum = 0;
    for ($i = strlen($code) - 1; $i >= 0; $i--) {
      $sum += (int)$code[$i] * ($weightflag?3:1);
      $weightflag = !$weightflag;
    }
    $code .= (10 - ($sum % 10)) % 10;
    
    return [$code];
};

$products = [];
$list = [
    [10, 'Express', 'Normal', 'Plus'],
    [30, 'Superior', 'Extreme', 'Mega'],
    [40, 'Felicidade', 'Extrema', 'Absurda'],
];
foreach($list as $item)
{ 
    $products[] = [[
        'id'            => $item[0],
        'name'          => 'Test ' . $item[1],
        'deliveryType'  => 'SHIPMENT',
        'sku'           =>[
            [
                'id'            => $item[0]+1,
                'name'          => $item[2],
                'description'   => 'Hello World!',
                'ean'           => $generateEAN($item[0]+1),
            ],
            [
                'id'            => $item[0]+2,
                'name'          => $item[3],
                'description'   => 'Hello Moon!',
                'ean'           => $generateEAN($item[0]+2),
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
    ]];
}
   
return $products;

