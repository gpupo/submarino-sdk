<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method integer getSequential()
 * @method setSequential(integer $sequential)
 * @method string getId()
 * @method setId(integer $quantity)
 * @method float getValue()
 * @method setValue(float $value)
 */
class PaymentMethod extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'sequential' => 'integer',
            'id'         => 'string',
            'value'      => 'number',
        ];
    }
}
