<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\Common\Entity\Collection;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;

/**
 * @coversNothing
 */
class ClientTest extends TestCaseAbstract
{
    public function testGerenciaUriDeRecurso()
    {
        $client = $this->factoryClient();
        $this->assertSame(
            'https://api-sandbox.bonmarketplace.com.br/sku',
            $client->getResourceUri('/sku')
        );
    }

    /**
     * @requires extension curl
     */
    public function testAcessoAListaDePedidos()
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $client = $this->factoryClient();

        $response = $client->get('/order');

        $this->assertInternalType('array', $response->getData()->getOrders());
        $this->assertInternalType('int', $response->getData()->getTotal());
    }

    /**
     * @requires extension curl
     */
    public function testAcessoAListaDeProdutos()
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $client = $this->factoryClient();
        $data = $client->get('/product');

        $this->assertHttpStatusCodeSuccess($data['httpStatusCode']);

        $this->assertInternalType('array', $data->getData()->getProducts());
    }

    /**
     * Retorna uma lista skus e o total de registros encontrados na pesquisa.
     *
     * @requires extension curl
     */
    public function testAcessoAListaDeSkus()
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $client = $this->factoryClient();
        $response = $client->get('/sku');

        $this->getLogger()->addDebug('Lista de SKUs', $response->toLog());

        $this->assertHttpStatusCodeSuccess($response->getHttpStatusCode());

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

            $this->getLogger()->addDebug(
                'Informações do SKU #'.$sku['id'],
                $response->toLog()
            );

            $this->assertHttpStatusCodeSuccess($response->getHttpStatusCode());
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

            $this->assertHttpStatusCodeSuccess(
                $response->getHttpStatusCode(),
                json_encode([$response->toArray(), $sku])
            );
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

            $this->assertSame($info['sellPrice'], $price->getSellPrice());

            $newSellPrice = number_format($info['sellPrice'] * 0.99, 2, '.', '');
            $price->setSellPrice($newSellPrice);
            $this->assertSame($newSellPrice, $price->getSellPrice());

            $changeData = $client->put('/sku/'.$sku['id'].'/price', $price->toJson());
            $this->assertHttpStatusCodeSuccess($changeData->getHttpStatusCode());

            $newResponse = $client->get('/sku/'.$sku['id']);
            $newPrice = Factory::factoryPrice($newResponse->getData()->getPrice());

            $this->assertSame($newSellPrice, $newPrice->getSellPrice());
        }
    }
}
