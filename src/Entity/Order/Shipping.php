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

/**

 * @method string getShippingEstimateId()
 * @method setShippingEstimateId(string $shippingEstimateId)
 * @method string getShippingMethodId()
 * @method setShippingMethodId(string $shippingMethodId)
 * @method string getShippingMethodName()
 * @method setShippingMethodName(string $shippingMethodName)
 * @method string getCalculationType()
 * @method setCalculationType(string $calculationType)
 * @method string getShippingMethodDisplayName()
 * @method setShippingMethodDisplayName(string $shippingMethodDisplayName)
 */
class Shipping extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'shippingEstimateId'        => 'string',
            'shippingMethodId'          => 'string',
            'shippingMethodName'        => 'string',
            'calculationType'           => 'string',
            'shippingMethodDisplayName' => 'string',
        ];
    }
}
