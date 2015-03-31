<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
