<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\EntityAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityInterface;

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
