<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Manager as SkuManager;

class Manager extends ManagerAbstract
{
    protected $entity = 'Product';

    protected $maps = [
        'save'      => ['POST', '/product'],
        'addSku'    => ['POST', '/product/{itemId}/sku'],
        'findById'  => ['GET', '/product/{itemId}'],
        'fetch'     => ['GET', '/product?offset={offset}&limit={limit}'],
    ];

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        foreach ($entity->getSku() as $sku) {
            if (!$existent->has($sku)) {
                $this->addSku($entity, $sku);
            } else {
                $this->updateSku($sku);
            }
        }

        return true;
    }

    public function updateSku(Sku $sku)
    {
        $manager = new SkuManager($this->getClient());
        
        return $manager->save($sku); 
    }

    public function addSku(Product $product, Sku $sku)
    {
        return $this->execute($this->factoryMap('addSku', ['itemId' => $product->getId()]), $sku->toJson());
    }
}
