<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Status extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'status'    => 'string',
            'shipped'   => 'object',
            'delivered' => 'object',
        ];
    }
}
