<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\EntityAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityInterface;

class Product extends EntityAbstract implements EntityInterface
{
    /**
     * Schema: Resources/Schema/Product.txt
     */
    public function getSchema()
    {
        return  [
            'id'            => 'string',
            'name'          => 'string',
            'sku'           => 'collection',
            'manufacturer'  => 'object',
            'deliveryType'  => 'string',
            'nbm'           => 'array',
        ];
    }
}
