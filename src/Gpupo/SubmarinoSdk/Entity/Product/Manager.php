<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;

class Manager extends ManagerAbstract
{
    protected $entity = 'Product';

    protected $maps = [
        'save'      => ['POST', '/product'],
        'addSku'    => ['POST', '/product/{itemId}'],
        'findById'  => ['GET', '/product/{itemId}'],
        'fetch'     => ['GET', '/product?offset={offset}&limit={limit}'],
    ];

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        return true;
    }

    public function addSku(Product $product, Sku $sku)
    {
    }
}
