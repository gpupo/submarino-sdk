<?php

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\CommonSdk\Entity\Collection;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;

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
        $response = $client->get('/order');

        $this->assertTrue(is_array($response->getData()->getOrders()));
        $this->assertInternalType('int', $response->getData()->getTotal());
    }

    public function testCadastraProduto()
    {
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

            $client = $this->factoryClient();
            $response = $client->post('/product', $product->toJson());

            $this->assertEquals(200, $response->getHttpStatusCode());
        }
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
        return $this->markTestIncomplete();
        
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
        return $this->markTestIncomplete();
        
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
        return $this->markTestIncomplete();
        
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $response = $client->get('/sku/' . $sku['id']);

            $info = $response->getData()->getPrice();
            $price = Factory::factoryPrice($info);

            $this->assertEquals($info['sellPrice'], $price->getSellPrice());

            $newSellPrice = $info['sellPrice'] * 0.9;
            $price->setSellPrice($newSellPrice);
            $this->assertEquals($newSellPrice, $price->getSellPrice());

            $changeData = $client->put('/sku/' . $sku['id'] . '/price', $price->toJson());

            print_r($changeData);

        }
    }

}
