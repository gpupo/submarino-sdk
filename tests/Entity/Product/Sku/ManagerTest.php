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

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Manager;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class ManagerTest extends TestCaseAbstract
{
    public function testAcessoAListaDeSkusCadastrados()
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());

        $list = $manager->fetch();

        $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionInterface', $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $item);
        }
    }

    /**
     * @dataProvider dataProviderSkus
     */
    public function testAcessaAInformacoesDeUmSku($id, $name)
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());
        $item = $manager->findById($id);
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $item);

        $this->assertEquals($id, $item->getId());
        $this->assertEquals($name, $item->getName());

        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Price', $item->getPrice());
    }

    public function testGerenciaAtualizacoes()
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $manager = new Manager($this->factoryClient());

        foreach ($this->dataProviderSkus() as $data) {
            $sku = new Sku($data);
            $sku->getPrice()->setSellPrice($sku->getPrice()->getSellPrice() - 0.01);
            $sku->setStockQuantity(rand(1, 8));
            $this->assertTrue($manager->save($sku));
        }
    }
}
