<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\CollectionAbstract;

class Sku extends CollectionAbstract
{
    public function __construct(array $elements = array())
    {
        $list = [];

        foreach ($elements as $product) {
            $list[] = new Sku\Sku($product);
        }

        parent::__construct($list);
    }
}
