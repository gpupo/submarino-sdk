<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
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
            'https://api.skyhub.com.br/sku',
            $client->getResourceUri('/sku')
        );
    }

    /**
     * @requires extension curl
     */
    public function testAcessoAListaDePedidos()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/list.json');
        $this->assertIsArray($response->getData()->getOrders());
        $this->assertIsInt($response->getData()->getTotal());
    }
}
