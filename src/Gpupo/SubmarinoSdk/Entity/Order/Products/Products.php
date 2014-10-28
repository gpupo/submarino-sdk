<?php

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
