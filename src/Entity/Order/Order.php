<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function toStatus()
    {
        return $this->piece('status');
    }
}
