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
                'deliveryType'  => 'Correios',
            ]],
        ];
    }
    
    protected function factory($data)
    {
        $sku = EntityManager::factory('Product', 'Sku')
            ->setId(2)->setName('Normal')->setDescription('Hello World!')
            ->setEan(array('0102999'))->setHeight(1)->setWidth(1)->setLength(1)
            ->setWeight(1)->setStockQuantity(1)->setEnable(true)
            ->setPrice(array('sellPrice' => 1, 'listPrice' => 2));
        
        $manufacturer = EntityManager::factory('Product', 'Manufacturer')
            ->setName('Foo')->setModel('Bar')->setWarrantyTime(30);
        
        $product = EntityManager::factory('Product', 'Product')
            ->setId($data['id'])->setName($data['name'])
            ->setDeliveryType($data['deliveryType'])
            ->setNbm(['number' => 1, 'origin' => 1])
            ->setManufacturer($manufacturer);
        
        $product->getSku()->add($sku);

        return $product;
    }
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPropriedadesEObjetos(array $data)
    {        
        $product = $this->factory($data);
        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Test Express', $product->getName());
        $this->assertEquals('Correios', $product->getDeliveryType());
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiNbmFormatado($data)
    {
        $product = $this->factory($data);
        $nbm = $product->getNbm();
        $this->assertEquals(1, $nbm['number']);
        $this->assertEquals(1, $nbm['origin']);
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
        $productSku = $product->getSku()->first();
        $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Sku', $productSku);
        $this->assertEquals('Normal', $productSku->getName());
        $this->assertEquals('Hello World!', $productSku->getDescription());
        $this->assertEquals('0102999', current($productSku->getEan()));
        $this->assertEquals(1, $productSku->getHeight(), 'Height');
        $this->assertEquals(1, $productSku->getWidth(), 'Width');
        $this->assertEquals(1, $productSku->getLength(), 'Length');
        $this->assertEquals(1, $productSku->getWeight(), 'Weight');
        $this->assertEquals(1, $productSku->getStockQuantity(), 'StockQuantity');
        $this->assertEquals(true, $productSku->getEnable(), 'Enable');
    }
    
    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiObjetoManufacturer($data)
    {               
        $product = $this->factory($data);
        $productManufacturer = $product->getManufacturer();
        $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Manufacturer', $productManufacturer);
        $this->assertEquals('Foo', $productManufacturer->getName());
        $this->assertEquals('Bar', $productManufacturer->getModel());
        $this->assertEquals('30', $productManufacturer->getWarrantyTime());

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
        
        return $array;
    }
    
    /**
     * @depends testEntregaJson
     */
    public function testJsonPossuiFabricanteFormatado(array $array)
    {
        $this->assertArrayHasKey('manufacturer', $array);
        
        foreach(array('name', 'model',  'warrantyTime') as $key) {
            $this->assertArrayHasKey($key, $array['manufacturer']);
        }
    }
}
