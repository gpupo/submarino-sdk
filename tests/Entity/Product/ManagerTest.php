<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('product')->setDryRun($response);
    }

    public function testObtemListaDeProdutosCadastrados()
    {
        $response = $this->factoryResponseFromFixture('fixture/Product/list.json');
        $list = $this->getManager($response)->fetch();
        $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionInterface', $list);

        foreach ($list as $product) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Product', $product);
        }
    }

    protected function factoryDetail()
    {
        $response = $this->factoryResponseFromFixture('fixture/Product/detail.json');

        return $this->getManager($response)->findById(9474);
    }

    public function testRecuperaInformacoesDeUmProdutoEspecifico()
    {
        $product = $this->factoryDetail();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Product', $product);
        $this->assertEquals($product->getId(), 9474);
    }

    public function testGerenciaUpdate()
    {
        $product = $this->factoryDetail();
        $manager = $this->getManager();

        $this->assertTrue($manager->save($product));

        $sku = $product->getSku()->current();
        $previous = clone $sku;
        $sku->setPrevious($previous);

        $this->assertTrue($manager->updateSku($sku));
    }
}
