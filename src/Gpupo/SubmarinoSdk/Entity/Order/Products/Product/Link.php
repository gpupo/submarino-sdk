<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Products\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Link extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'id'  => 'string',
            'rel' => 'string',
            'href'=> 'string',
        ];
    }
}
