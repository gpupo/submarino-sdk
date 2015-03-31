<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Products;

use Gpupo\Common\Entity\CollectionAbstract;

class Products extends CollectionAbstract
{
    public function __construct(array $elements = array())
    {
        $list = [];

        foreach ($elements as $product) {
            $list[] = new Product\Product($product);
        }

        parent::__construct($list);
    }
}
