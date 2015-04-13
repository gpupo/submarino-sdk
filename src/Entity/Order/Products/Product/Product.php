<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Products\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Product extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'link'      => 'object',
            'quantity'  => 'integer',
            'price'     => 'number',
            'freight'   => 'number',
            'discount'  => 'number',
        ];
    }
}
