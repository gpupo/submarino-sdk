<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Shipped extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'trackingProtocol'      => 'string',
            'estimatedDelivery'     => 'string',
            'deliveredCarrierDate'  => 'string'
        ];
    }
}
