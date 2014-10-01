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
            'sku'           => 'object',
            'manufacturer'  => 'object',
            'deliveryType'  => 'string',
            'nbm'           => 'array',
        ];
    }

    public function has(Sku\Sku $sku)
    {
        return $this->getSku()->hasId($sku->getId());
    }
}
