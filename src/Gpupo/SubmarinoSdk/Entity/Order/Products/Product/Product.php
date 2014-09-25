<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Products\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Product extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'link'      => 'object',
            'quantity'  => 'integer',
            'price'     => 'number',
            'freight'   => 'number',
            'discount'  => 'number',
        ];
    }
}
