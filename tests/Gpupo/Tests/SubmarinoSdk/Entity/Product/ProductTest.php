<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityManager;

class ProductTest extends TestCaseAbstract
{        
    public function dataProviderProducts()
    {
        return [
            [[
                'id'            => 1,
                'name'          => 'Test Express',
                'deliveryType'  => 'SHIPMENT',
                'sku'           =>[
                    [
                        'id'            => 2,
                        'name'          => 'Normal',
                        'description'   => 'Hello World!',
                        'ean'           => ['0102999984123','0102999984124'],
                    ],
                    [
                        'id'            => 3,
                        'name'          => 'Plus',
                        'description'   => 'Hello Moon!',
                        'ean'           => ['0102999984125','0102999984126'],
                    ],
                ],
                'manufacturer' => [
                    'name'          => 'Foo',
                    'model'         => 'Bar',
                    'warrantyTime'  => 30,
                ],
                'nbm'   => [
                    'number' => 1041011,
                    'origin' => 2,
                ],
            ]],
        ];
    }
    
    protected function factory($data)
    {
        $manufacturer = EntityManager::factory('Product', 'Manufacturer')
            ->setName($data['manufacturer']['name'])
            ->setModel($data['manufacturer']['model'])
            ->setWarrantyTime($data['manufacturer']['warrantyTime']);
        
        $product = EntityManager::factory('Product', 'Product')
            ->setId($data['id'])->setName($data['name'])
            ->setDeliveryType($data['deliveryType'])
            ->setNbm($data['nbm'])
            ->setManufacturer($manufacturer);

        foreach ($data['sku'] as $item) {
            $sku = EntityManager::factory('Product', 'Sku')
                ->setId($item['id'])->setName($item['name'])
                ->setDescription($item['description'])
                ->setEan($item['ean'])->setHeight(1)->setWidth(1)->setLength(1)
                ->setWeight(1)->setStockQuantity(1)->setEnable(true)
                ->setPrice(array('sellPrice' => 1, 'listPrice' => 2));

            $product->getSku()->add($sku);
        }
        
        return $product;
    }
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPropriedadesEObjetos(array $data)
    {        
        $product = $this->factory($data);
        $this->assertEquals(1, $product->getId());
        $this->assertEquals($data['name'], $product->getName());
        $this->assertEquals($data['deliveryType'], $product->getDeliveryType());
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiNbmFormatado($data)
    {
        $product = $this->factory($data);
        $nbm = $product->getNbm();
        $this->assertEquals($data['nbm']['number'], $nbm['number']);
        $this->assertEquals($data['nbm']['origin'], $nbm['origin']);
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPrecoFormatado($data)
    {
        $product = $this->factory($data);
        $price = $product->getSku()->first()->getPrice();
        $this->assertEquals(1, $price['sellPrice']);
        $this->assertEquals(2, $price['listPrice']);
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiUmaColecaoDeSkus($data)
    {
        $product = $this->factory($data);
        
        foreach ($data['sku'] as $item) {
            $productSku = $product->getSku()->current();
            $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Sku', $productSku);
            $this->assertEquals($item['name'], $productSku->getName());
            $this->assertEquals($item['description'], $productSku->getDescription());
            
            $skuEan = $productSku->getEan();
            foreach ($item['ean'] as $ean) {
                $this->assertEquals($ean, current($skuEan));
                next($skuEan);
            }
            
            $this->assertEquals(1, $productSku->getHeight(), 'Height');
            $this->assertEquals(1, $productSku->getWidth(), 'Width');
            $this->assertEquals(1, $productSku->getLength(), 'Length');
            $this->assertEquals(1, $productSku->getWeight(), 'Weight');
            $this->assertEquals(1, $productSku->getStockQuantity(), 'StockQuantity');
            $this->assertEquals(true, $productSku->getEnable(), 'Enable');
            $product->getSku()->next();
        }
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiObjetoManufacturer($data)
    {               
        $product = $this->factory($data);
        $productManufacturer = $product->getManufacturer();
        $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Manufacturer', $productManufacturer);
        $this->assertEquals($data['manufacturer']['name'], $productManufacturer->getName());
        $this->assertEquals($data['manufacturer']['model'], $productManufacturer->getModel());
        $this->assertEquals($data['manufacturer']['warrantyTime'], $productManufacturer->getWarrantyTime());

        return $product;
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testEntregaJson($data)
    {   
        $product = $this->factory($data);
        $json = $product->toJson();
        $array = json_decode($json, true);
        
        $this->assertArrayHasKey('sku', $array);
        $this->assertArrayHasKey('description', current($array['sku']));
        
        $this->assertArrayHasKey('manufacturer', $array);
        foreach(array('name', 'model',  'warrantyTime') as $key) {
            $this->assertArrayHasKey($key, $array['manufacturer']);
        }
        
        return $array;
    }
}
