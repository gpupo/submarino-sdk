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

        foreach (current($this->dataProviderProducts()) as $data) {
            $manufacturer = Factory::factoryManufacturer($data['manufacturer']);

            $product = Factory::factoryProduct($data)
                ->setManufacturer($manufacturer);

            foreach ($data['sku'] as $item) {
                $sku = Factory::factorySku()
                    ->setId($item['id'])->setName($item['name'])
                    ->setDescription($item['description'])
                    ->setEan($item['ean'])->setHeight(1)->setWidth(1)->setLength(1)
                    ->setWeight(1)->setStockQuantity(1)->setEnable(true)
                    ->setPrice(array('sellPrice' => 1, 'listPrice' => 2));

                $product->getSku()->add($sku);
            }

            $this->assertTrue($manager->save($product));
        }
    }

}
