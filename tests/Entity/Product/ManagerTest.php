<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
}
