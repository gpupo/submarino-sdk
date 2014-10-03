<?php

namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Manager extends ManagerAbstract
{
    protected $entity = 'Sku';

    protected $maps = [
        'findById'      => ['GET', '/sku/{itemId}'],
        'fetch'         => ['GET', '/sku?offset={offset}&limit={limit}'],
        'savePrice'     => ['PUT', '/sku/{itemId}/price'],
        'saveStock'     => ['PUT', '/sku/{itemId}/stock'],
        'saveStatus'    => ['PUT', '/sku/{itemId}/status'],
    ];

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        if ($this->atributesDiff($existent->getPrice(), $entity->getPrice(), ['listPrice', 'salePrice'])) {
            $this->savePrice($entity);
        }

        return true;
    }

    public function savePrice(Sku $sku)
    {
        return $this->execute($this->factoryMap('savePrice', ['itemId' => $sku->getId()]), $sku->getPrice()->toJson());
    }
}
