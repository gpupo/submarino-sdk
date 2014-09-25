<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Pf extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'cpf'   => 'string',
            'name'  => 'string',
       
        ];
    }
}
