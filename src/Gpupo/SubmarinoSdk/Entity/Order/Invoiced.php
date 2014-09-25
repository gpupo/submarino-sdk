<?php

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Invoiced extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'key'       => 'string',
            'number'    => 'integer',
            'line'      => 'integer',
            'issueDate' => 'string',
        ];
    }
}
