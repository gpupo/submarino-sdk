<?php

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
