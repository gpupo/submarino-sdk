<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\Product\Factory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;
use Gpupo\Tests\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    public function testObtemListaDeProdutosCadastrados()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());
        $list = $manager->fetch();
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\CollectionInterface',
            $list);

        return $list;
    }

    /**
     * @depends testObtemListaDeProdutosCadastrados
     */
    public function testRecuperaInformacoesDeUmPedidoEspecifico($list)
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());

        foreach ($list as $product) {
            $info = $manager->findById($product->getId());

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Product',
            $info);

            $this->assertEquals($product->getId(), $info->getId());
        }
    }

    public function testGerenciaUpdate()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());

        foreach ($this->dataProviderProducts() as $array) {
            $data = current($array);
            $product = Factory::factoryProduct($data);
            $this->assertTrue($manager->save($product), $product);
        }
    }
}
