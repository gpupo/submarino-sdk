<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $entity = 'Sku';

    protected $maps = [
        'findById'   => ['GET', '/sku/{itemId}'],
        'fetch'      => ['GET', '/sku?offset={offset}&limit={limit}'],
        'savePrice'  => ['PUT', '/sku/{itemId}/price'],
        'saveStock'  => ['PUT', '/sku/{itemId}/stock'],
        'saveStatus' => ['PUT', '/sku/{itemId}/status'],
    ];

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        if ($this->attributesDiff($existent->getPrice(), $entity->getPrice(), ['listPrice', 'sellPrice'])) {
            $this->savePrice($entity);
        }

        if ($this->attributesDiff($existent, $entity, ['stockQuantity'])) {
            $this->saveStock($entity);
        }

        if ($this->attributesDiff($existent, $entity, ['enable'])) {
            $this->saveStatus($entity);
        }

        return true;
    }

    public function savePrice(Sku $sku)
    {
        return $this->execute($this->factoryMap('savePrice', ['itemId' => $sku->getId()]), $sku->getPrice()->toJson());
    }

    public function saveStock(Sku $sku)
    {
        return $this->execute($this->factoryMap('saveStock', ['itemId' => $sku->getId()]), $sku->toJson('Stock'));
    }

    public function saveStatus(Sku $sku)
    {
        return $this->execute($this->factoryMap('saveStatus', ['itemId' => $sku->getId()]), $sku->toJson('Status'));
    }
}
