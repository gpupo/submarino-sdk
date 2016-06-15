<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Order\Products;

use Gpupo\CommonSdk\Entity\CollectionAbstract;

class Products extends CollectionAbstract
{
    public function factoryElement($data)
    {
        return new Product\Product($data);
    }
}
