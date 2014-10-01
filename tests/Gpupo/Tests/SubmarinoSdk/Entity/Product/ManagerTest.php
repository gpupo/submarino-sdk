<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;

class ManagerTest extends TestCaseAbstract
{
    public function testGerenciaUpdate()
    {
        $manager = new Manager($this->factoryClient());

        foreach ($this->dataProviderProducts() as $array) {
            
            $data = current($array);
            $product = Factory::factoryProduct($data);

            foreach ($data['sku'] as $item) {
                $sku = Factory::factorySku($item);
                $product->getSku()->add($sku);
            }

            $this->assertTrue($manager->save($product), $product);
        }
    }

}
