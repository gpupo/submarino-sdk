<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('sku')->setDryRun($response);
    }

    public function testAcessoAListaDeSkusCadastrados()
    {
        $response = $this->factoryResponseFromFixture('fixture/Product/Sku/list.json');
        $list = $this->getManager($response)->fetch();

        $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionInterface', $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $item);
        }
    }

    public function testAcessaAInformacoesDeUmSku()
    {
        $response = $this->factoryResponseFromFixture('fixture/Product/Sku/detail.json');
        $sku = $this->getManager($response)->findById(9474);
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $sku);
        $this->assertEquals(9474, $sku->getId());
    }
}
