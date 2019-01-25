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

namespace Gpupo\SubmarinoSdk\Tests\Entity\Product;

use Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;
use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Order;
use Gpupo\CommonSchema\ArrayCollection\Trading\Product\Product;
use Gpupo\Common\Entity\CollectionInterface;

class ManagerTest extends TestCaseAbstract
{
    public function testGetProducts()
    {
        $response = $this->factoryResponseFromFixture('mockup/products/list.json');
        $list = $this->getManager($response)->fetch();
        $this->assertInstanceOf(CollectionInterface::class, $list);
        foreach ($list as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }
    }

    public function testRecuperaInformacoesDeUmProdutoEspecifico()
    {
        $product = $this->factoryDetail();
        $this->assertInstanceOf(Product::class, $product);
        $this->assertSame((int) $product->getId(), 9474);
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

    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('product')->setDryRun($response);
    }

    protected function factoryDetail()
    {
        $response = $this->factoryResponseFromFixture('mockup/products/detail.json');

        return $this->getManager($response)->findById(9474);
    }
}
