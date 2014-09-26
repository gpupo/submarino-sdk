<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Delivered extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'deliveredCustomerDate' => 'string',
        ];
    }
}
