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

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\SubmarinoSdk\Entity\Order\Order;

class ManagerTest extends OrderTestCaseAbstract
{
    public function testObtemListaPedidos()
    {
        $list = $this->getList();
        $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionInterface',
            $list);

        return $list;
    }

    public function testObtémAListaDePedidosRecémAprovadosEQueEsperamProcessamento()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/list.json');
        $manager = $this->factoryManager()->setDryRun($response);
        $list = $manager->fetchQueue();
        $this->assertInstanceOf('\Gpupo\Common\Entity\CollectionInterface', $list);
    }

    public function testRecuperaInformacoesDeUmPedidoEspecifico()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/detail.json');
        $order = $this->factoryManager()->setDryRun($response)->findById(589);
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Order', $order);
        $this->assertSame(589, $order->getId());
        $this->assertSame('03-589-01', $order->getSiteId());
        $this->assertSame('SUBMARINO', $order->getStore());
        $this->assertSame('APROVED', $order->getStatus()->getStatus());

        return $order;
    }

    /**
     * @depends testRecuperaInformacoesDeUmPedidoEspecifico
     */
    public function testAtualizaStatusDeUmPedido(Order $order)
    {
        $flux = [
            'APROVED' => 'PROCESSING',
        ];

        $manager = $this->factoryManager()->setDryRun();
        $currentStatus = $order->getStatus()->__toString();
        $newStatus = $flux[$currentStatus];
        $order->getStatus()->setStatus($newStatus);
        $this->assertTrue($manager->saveStatus($order));

        return $order;
    }

    /**
     * @depends testAtualizaStatusDeUmPedido
     */
    public function testAtualizaDadosDeEnvioDeUmPedido(Order $order)
    {
        $flux = [
            'PROCESSING' => 'SHIPPED',
        ];

        $manager = $this->factoryManager()->setDryRun();
        $currentStatus = $order->getStatus()->__toString();
        $newStatus = $flux[$currentStatus];
        $order->getStatus()->setStatus($newStatus)->getShipped()
            ->setEstimatedDelivery('2014-12-01 10:00:00')
            ->setDeliveredCarrierDate(date('Y-m-d H:i:s'));

        $this->assertTrue($manager->saveStatus($order));

        return $order;
    }

    /**
     * @depends testAtualizaDadosDeEnvioDeUmPedido
     */
    public function testAtualizaDadosDeEntregaDeUmPedido(Order $order)
    {
        $flux = [
            'SHIPPED' => 'DELIVERED',
        ];

        $manager = $this->factoryManager()->setDryRun();
        $currentStatus = $order->getStatus()->__toString();
        $newStatus = $flux[$currentStatus];
        $order->getStatus()->setStatus($newStatus)
            ->getDelivered()->setDeliveredCustomerDate(date('Y-m-d H:i:s'));
        $this->assertTrue($manager->saveStatus($order));
    }
}
