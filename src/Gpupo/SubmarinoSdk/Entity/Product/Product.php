<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Product extends EntityAbstract implements EntityInterface
{

    protected $entity = 'Product';

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
