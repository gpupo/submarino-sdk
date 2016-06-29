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

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Manager as SkuManager;

class Manager extends ManagerAbstract
{
    protected $entity = 'Product';

    protected $maps = [
        'save'     => ['POST', '/product'],
        'addSku'   => ['POST', '/product/{itemId}/sku'],
        'findById' => ['GET', '/product/{itemId}'],
        'fetch'    => ['GET', '/product?offset={offset}&limit={limit}'],
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

    public function updateSku(Sku\Sku $sku)
    {
        $manager = new SkuManager($this->getClient());

        return $manager->update($sku, $sku->getPrevious());
    }

    public function addSku(Product $product, Sku\Sku $sku)
    {
        return $this->execute($this->factoryMap('addSku', ['itemId' => $product->getId()]), $sku->toJson());
    }
}
