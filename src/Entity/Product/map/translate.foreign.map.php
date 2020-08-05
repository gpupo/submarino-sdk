<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

$array = [
    'sku' => $foreign->get('seller_product_id'),
    'name' => $foreign->get('name'),
    'description' => $foreign->get('description'),
    'qty' => $foreign->get('quantity'),
    'price' => $foreign->get('price'),
    'promotional_price' => $foreign->get('promotional_price'),
    'cost' => $foreign->get('cost'),
    'weight' => $foreign->get('weight'),
    'height' => $foreign->get('height'),
    'width' => $foreign->get('width'),
    'length' => $foreign->get('length'),
    'brand' => $foreign->get('brand'),
    'ean' => $foreign->get('gtin'),
    'images' => $foreign->get('images'),
    'status' => 'enabled',
    'categories' => $foreign->get('categories'),
];
 
$array['specifications'] = [
    [
        'key' => 'mpn',
        'value' => $foreign->get('mpn'),
    ],
];

return $array;
