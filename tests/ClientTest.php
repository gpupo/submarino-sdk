<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\Common\Entity\Collection;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;

class ClientTest extends TestCaseAbstract
{
    /**
     * @requires extension curl
     */
    public function testAcessoAListaDePedidos()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $client = $this->factoryClient();

        $response = $client->get('/order');

        $this->assertTrue(is_array($response->getData()->getOrders()));
        $this->assertInternalType('int', $response->getData()->getTotal());
    }

    /**
     * @requires extension curl
     */
    public function testAcessoAListaDeProdutos()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $client = $this->factoryClient();
        $data = $client->get('/product');

        $this->assertEquals(200, $data['httpStatusCode']);
        $this->assertTrue(is_array($data->getData()->getProducts()));
    }

    /**
     * Retorna uma lista skus e o total de registros encontrados na pesquisa.
     *
     * @requires extension curl
     */
    public function testAcessoAListaDeSkus()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $client = $this->factoryClient();
        $response = $client->get('/sku');

        $this->getLogger()->addDebug('Lista de SKUs', $response->toLog());

        $this->assertEquals(200, $response->getHttpStatusCode(), $response->toJson());
        $this->assertArrayHasKey('skus', $response->getData()->toArray());

        return $response->getData();
    }

    /**
     * @depends testAcessoAListaDeSkus
     */
    public function testRetornaInformacoesDoSkuInformado(Collection $data)
    {
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $response = $client->get('/sku/'.$sku['id']);

            $this->getLogger()->addDebug('Informações do SKU #'.$sku['id'],
                $response->toLog());

            $this->assertEquals(200, $response->getHttpStatusCode());
            $this->assertArrayHasKey('id', $response->getData(), json_encode($data));
        }
    }

    /**
     * @depends testAcessoAListaDeSkus
     */
    public function testAtualizaEstoqueDoSkuInformado(Collection $data)
    {
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $body = json_encode(['quantity' => 2]);
            $response = $client->put('/sku/'.$sku['id'].'/stock', $body);

            $this->assertEquals(200, $response->getHttpStatusCode(), json_encode([$response->toArray(), $sku]));
        }
    }

    /**
     * @depends testAcessoAListaDeSkus
     */
    public function testAtualizaPrecoDoSkuInformado(Collection $data)
    {
        foreach ($data->getSkus() as $sku) {
            $client = $this->factoryClient();
            $response = $client->get('/sku/'.$sku['id']);

            $info = $response->getData()->getPrice();
            $price = Factory::factoryPrice($info);

            $this->assertEquals($info['sellPrice'], $price->getSellPrice());

            $newSellPrice = number_format($info['sellPrice'] * 0.99, 2, '.', '');
            $price->setSellPrice($newSellPrice);
            $this->assertEquals($newSellPrice, $price->getSellPrice());

            $changeData = $client->put('/sku/'.$sku['id'].'/price', $price->toJson());
            $this->assertEquals(200, $changeData->getHttpStatusCode());

            $newResponse = $client->get('/sku/'.$sku['id']);
            $newPrice = Factory::factoryPrice($newResponse->getData()->getPrice());

            $this->assertEquals($newSellPrice, $newPrice->getSellPrice());
        }
    }
}
