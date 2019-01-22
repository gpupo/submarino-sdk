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

namespace Gpupo\SubmarinoSdk\Tests;

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
        $response = $this->factoryResponseFromFixture('fixture/Order/list.json');
        $this->assertInternalType('array', $response->getData()->getOrders());
        $this->assertInternalType('int', $response->getData()->getTotal());
    }
}
