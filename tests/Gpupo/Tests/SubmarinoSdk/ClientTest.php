<?php

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Collection;
use Gpupo\SubmarinoSdk\Entity\EntityFactory;
use Gpupo\SubmarinoSdk\Entity\Product\Product;
use Gpupo\SubmarinoSdk\Entity\Product\Sku;
use Gpupo\SubmarinoSdk\Entity\Product\Manufacturer;

class ClientTest extends TestCaseAbstract
{    
    public function testSimpleCurl()
    {       
        $client = $this->factoryClient();
        $curlClient = $client->factoryRequest('/order');
        $response = curl_exec($curlClient);
        $array = json_decode($response, true);
        $this->assertTrue(is_array($array['orders']));
        $this->assertInternalType('int', $array['total']);
        curl_close($curlClient);
    }
    
    public function testGetOrder()
    {       
        $client = $this->factoryClient();
        $request = $client->get('/order');
        $array = $request['response'];
        
        $this->assertTrue(is_array($array['orders']));
        $this->assertInternalType('int', $array['total']);
    }
        
    public function testCadastraProduto()
    {       
        $product = new Product;
        
        $product->setId(4526)->setName('Perfume Café Classique EDT Feminino')
            ->setDeliveryType('SHIPMENT')
            ->setNbm(array('number' => 1041011, 'origin' => 2));

        $sku = new Sku;
        $sku->setId(19381)->setName('90 ml')
            ->setDescription('Sutil e refinada, esta fragrância oriental possui uma combinação')
            ->setEan(array('3331430066043'))->setHeight(0.3)->setWidth(0.3)
            ->setLength(0.3)->setWeight(0.5)->setStockQuantity(1)->setEnable(true)
            ->setPrice(array('sellPrice' => 104.90, 'listPrice' => 123.39));
        $product->getSku()->add($sku);
        
        $manufacturer = new Manufacturer;
        $manufacturer->setName('Café')->setModel('3331430066043')
            ->setWarrantyTime(30);
        $product->setManufacturer($manufacturer);
   
        $client = $this->factoryClient();
        $data = $client->post('/product', $product->toJson());
        
        $this->assertEquals(200, $data['httpStatusCode'], json_encode($data));
    }
    
    public function testGetProducts()
    {       
        $client = $this->factoryClient();
        $data = $client->get('/product');
        
        $this->assertEquals(200, $data['httpStatusCode']);
        
        print_r($data);
    }
    
    /**
     * Retorna uma lista skus e o total de registros encontrados na pesquisa.
     */
    public function testRetornaListaDeSkus()
    {       
        $client = $this->factoryClient();
        $response = $client->get('/sku');
        
        $this->assertEquals(200, $response->getHttpStatusCode(),$response->toJson());
        $this->assertArrayHasKey('skus', $response->getData()->toArray());
        
        return $response->getData();
    }
    
    /**
     * @depends testRetornaListaDeSkus
     */
    public function testRetornaInformacoesDoSkuInformado(Collection $data)
    {       
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $response = $client->get('/sku/' . $sku['id']);
            $this->assertEquals(200, $response->getHttpStatusCode());
            $this->assertArrayHasKey('id', $response->getData(), json_encode($data));
        }
    }
    
    /**
     * @depends testRetornaListaDeSkus
     */
    public function testAtualizaSituacaoDoSkuInformado(Collection $data)
    {       
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();

            $array = ["enable" => true];
            
            $body = json_encode(array($array));
            $response = $client->put('/sku/' . $sku['id'] . '/status', $body);
            
            $this->assertEquals(200, $response->getHttpStatusCode(), json_encode([$response->toArray(), $sku]));
        }
    }
    
    /**
     * @depends testRetornaListaDeSkus
     */
    public function testAtualizaEstoqueDoSkuInformado(Collection $data)
    {       
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();

            $array = ["quantity" => 2];
            
            $body = json_encode(array($array));
            $response = $client->put('/sku/' . $sku['id'] . '/stock', $body);
            
            $this->assertEquals(200, $response->getHttpStatusCode(), json_encode([$response->toArray(), $sku]));
        }
    }
    
    /**
     * @depends testRetornaListaDeSkus
     */
    public function testAtualizaPrecoDoSkuInformado(Collection $data)
    {       
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $response = $client->get('/sku/' . $sku['id']);
            
            $info = $response->getData()->getPrice();
            $price = EntityFactory::factory('Product', 'Price', $info);
            
            $this->assertEquals($info['sellPrice'], $price->getSellPrice());
            
            $newSellPrice = $info['sellPrice'] * 0.9;
            $price->setSellPrice($newSellPrice);
            $this->assertEquals($newSellPrice, $price->getSellPrice());
            
            $changeData = $client->put('/sku/' . $sku['id'] . '/price', $price->toJson());
            
            print_r($changeData);
            
        }
    }
    
}
