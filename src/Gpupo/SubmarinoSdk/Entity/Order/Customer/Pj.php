<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Pj extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'cnpj'          => 'string',
            'corporateName' => 'string',
        ];
    }
}
