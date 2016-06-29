<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
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
        $this->assertSame(9474, (int) $sku->getId());
    }
}
