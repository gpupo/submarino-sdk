<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Manufacturer extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'name'          => 'string',
            'model'         => 'string',
            'warrantyTime'  => 'integer',
        ];
    }
}
