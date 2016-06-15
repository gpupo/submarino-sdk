<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Shipped extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'trackingUrl'          => 'string',
            'trackingProtocol'     => 'string',
            'estimatedDelivery'    => 'string',
            'deliveredCarrierDate' => 'string',
            'carrierName'          => 'string',
        ];
    }

    public function setRequired()
    {
        return $this->setRequiredSchema([
            'estimatedDelivery',
            'deliveredCarrierDate',
        ]);
    }
}
