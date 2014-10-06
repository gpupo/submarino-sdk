<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

class ManagerTest extends OrderTestCaseAbstract
{
    public function testObtemListaPedidos()
    {
        $list = $this->getList();
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\CollectionInterface',
            $list);

        return $list;
    }

    /**
     *
     * @depends testObtemListaPedidos
     */
    public function testRecuperaInformacoesDeUmPedidoEspecifico($list)
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        foreach ($list as $order) {
            $info = $this->factoryManager()->findById($order->getId());

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Order',
            $info);

            $this->assertEquals($order->getId(), $info->getId());
            $this->assertEquals($order->getSiteId(), $info->getSiteId());
            $this->assertEquals($order->getStore(), $info->getStore());
            $this->assertEquals($order->getStatus(), $info->getStatus());
        }
    }

    /**
     *
     * @depends testObtemListaPedidos
     */
    public function testAtualizaStatusDeUmPedido($list)
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $flux = [
            'APROVED'       => 'PROCESSING',
        ];

        $i = 0;
        foreach ($list as $order) {
            $i++;
            $manager = $this->factoryManager();
            $currentStatus = $order->getStatus()->__toString();
            if (array_key_exists($currentStatus, $flux)) {
                $newStatus = $flux[$currentStatus];
                $order->getStatus()->setStatus($newStatus);
                $this->assertTrue($manager->saveStatus($order));
                $orderUpdated = $manager->findById($order->getId());

                $this->assertEquals($newStatus, $orderUpdated->getStatus()->__toString());
            }
        }

        if ($i < 1) {
           $this->markTestSkipped('Sem Pedidos para atualizar');
        }
    }

    /**
     *
     * @depends testObtemListaPedidos
     */
    public function testAtualizaDadosDeEnvioDeUmPedido($list)
    {
        if (!$this->hasToken()) {
            return $this->markTestSkipped('API Token ausente');
        }

        $flux = [
            'PROCESSING'    => 'SHIPPED',
            //'SHIPPED'       => 'DELIVERED',
        ];
            
        $manager = $this->factoryManager();
        
        foreach ($list as $order) {
            $currentStatus = $order->getStatus()->__toString();
            if (array_key_exists($currentStatus, $flux)) {
                $newStatus = $flux[$currentStatus];
                $order->getStatus()->setStatus($newStatus);
                
                echo "\n" . $order->getStatus()->toJson() . "\n";

                continue;
                $this->assertTrue($manager->saveStatus($order));
                $orderUpdated = $manager->findById($order->getId());

                $this->assertEquals($newStatus, $orderUpdated->getStatus());
            }
        }
    }
}
