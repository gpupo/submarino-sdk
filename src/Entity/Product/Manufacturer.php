<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Manufacturer extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'name'         => 'string',
            'model'        => 'string',
            'warrantyTime' => 'integer',
        ];
    }
}
