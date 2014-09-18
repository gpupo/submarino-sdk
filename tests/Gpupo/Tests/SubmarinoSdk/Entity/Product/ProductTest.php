<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Product;
use Gpupo\SubmarinoSdk\Entity\Product\Sku;
use Gpupo\SubmarinoSdk\Entity\Product\Manufacturer;

class ProductTest extends TestCaseAbstract
{    
    public function testPossuiPropriedadesEObjetos()
    {
        $product = new Product;
        
        $product->setId(1)->setName('Test Express')->setDeliveryType('Correios')
            ->setNbm(array('number' => 1, 'origin' => 1));
        
        $sku = new Sku;
        $sku->setId(2)->setName('Normal')->setDescription('Hello World!')
            ->setEan(array('0102999'))->setHeight(1)->setWidth(1)->setLength(1)
            ->setWeight(1)->setStockQuantity(1)->setEnable(true)
            ->setPrice(array('sellPrice' => 1, 'listPrice' => 2));
        $product->getSku()->add($sku);
        
        $manufacturer = new Manufacturer;
        $manufacturer->setName('Foo')->setModel('Bar')->setWarrantyTime(30);
        $product->setManufacturer($manufacturer);
        
        $this->assertEquals(1, $product->getId());
        $this->assertEquals('Test Express', $product->getName());
        $this->assertEquals('Correios', $product->getDeliveryType());
               
        return $product;
    }
    
    /**
     * @depends testPossuiPropriedadesEObjetos
     */
    public function testPossuiNbmFormatado($product)
    {
        $nbm = $product->getNbm();
        $this->assertEquals(1, $nbm['number']);
        $this->assertEquals(1, $nbm['origin']);
        
        return $product;
    }
    
    /**
     * @depends testPossuiNbmFormatado
     */
    public function testPossuiPrecoFormatado($product)
    {
        $price = $product->getSku()->first()->getPrice();
        $this->assertEquals(1, $price['sellPrice']);
        $this->assertEquals(2, $price['listPrice']);
           
        return $product;
    }
    
    /**
     * @depends testPossuiPrecoFormatado
     */
    public function testPossuiUmaColecaoDeSkus($product)
    {
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
        
        return $product;
    }
    
    /**
     * 
     * @depends testPossuiUmaColecaoDeSkus
     */
    public function testPossuiObjetoManufacturer($product)
    {               
        $productManufacturer = $product->getManufacturer();
        $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Manufacturer', $productManufacturer);
        $this->assertEquals('Foo', $productManufacturer->getName());
        $this->assertEquals('Bar', $productManufacturer->getModel());
        $this->assertEquals('30 dias', $productManufacturer->getWarrantyTime());

        return $product;
    }
    
    /**
     * 
     * @depends testPossuiObjetoManufacturer
     */
    public function testEntregaJson(Product $product)
    {                       
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
