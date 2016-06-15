<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class DeliveryAddress extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'state'          => 'string',
            'number'         => 'string',
            'country'        => 'string',
            'street'         => 'string',
            'additionalInfo' => 'string',
            'neighborhood'   => 'string',
            'city'           => 'string',
            'zipcode'        => 'string',
            'reference'      => 'string',
        ];
    }
}
