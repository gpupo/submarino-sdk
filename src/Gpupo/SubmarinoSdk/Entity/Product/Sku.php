<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\EntityAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityInterface;

class Sku extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'id'            => 'string',
            'name'          => 'string',
            'description'   => 'object',
            'ean'           => 'array',
            'height'        => 'number',
            'width'         => 'number',
            'length'        => 'number',
            'weight'        => 'number',
            'stockQuantity' => 'integer',
            'enable'        => 'boolean',
            'price'         => 'array',
        ];
    }
}
