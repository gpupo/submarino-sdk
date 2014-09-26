<?php

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Order extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'id'                    => 'string',
            'siteId'                => 'string',
            'store'                 => 'string',
            'purchaseDate'          => 'string',
            'lastUpdate'            => 'string',
            'status'                => 'object',
            'invoiced'              => 'object',
            'estimatedDeliveryDate' => 'string',
            'customer'              => 'object',
            'totalAmount'           => 'number',
            'totalFreight'          => 'number',
            'totalDiscount'         => 'number',
            'products'              => 'object',
        ];
    }

    public function toSaveStatus()
    {
        return json_encode(['status' => $this->getStatus()]);
    }
}
