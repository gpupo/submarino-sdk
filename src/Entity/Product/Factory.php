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

use Gpupo\CommonSdk\Entity\FactoryAbstract;

class Factory extends FactoryAbstract
{
    public static function factorySku($data = null)
    {
        return new Sku\Sku($data);
    }

    public static function factoryPrice($data = null)
    {
        return new Sku\Price($data);
    }
}
