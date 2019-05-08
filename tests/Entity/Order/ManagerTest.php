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

namespace Gpupo\SubmarinoSdk\Tests\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSchema\ORM\Entity\Trading\Order\Order;
use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;
use Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class ManagerTest extends TestCaseAbstract
{
    public function testObtemListaPedidos()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/list.json');
        $list = $this->getManager($response)->fetch();
        $this->assertInstanceOf(CollectionInterface::class, $list, 'Assert 1');
        $this->assertInstanceOf(MetadataContainer::class, $list, 'Assert 2');
        foreach ($list as $orderList) {
            $this->assertInstanceOf(CollectionInterface::class, $orderList, 'Assert Order');
        }

        return $list;
    }

    public function testObtémAListaDePedidosRecémAprovadosEQueEsperamProcessamento()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/queue.json');
        $list = $this->getManager($response)->fetchQueue();
        $this->assertInstanceOf(CollectionInterface::class, $list);
        $this->assertInstanceOf(MetadataContainer::class, $list);
        foreach ($list as $order) {
            $this->assertInstanceOf(Order::class, $order, 'Assert Order');
        }

        return $list;
    }

    public function testRecuperaInformacoesDeUmPedidoEspecifico()
    {
        $response = $this->factoryResponseFromFixture('mockup/orders/detail.json');
        $order = $this->getManager($response)->findById(589);
        $this->assertInstanceOf(CollectionInterface::class, $order, 'Assert Order');
    }

    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('order')->setDryRun($response);
    }
}
