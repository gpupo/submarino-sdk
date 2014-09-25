<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Customer extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'pf'                => 'object',
            'pj'                => 'object',
            'deliveryAddress'   => 'object',
            'telephones'        => 'object'
        ];
    }
}
