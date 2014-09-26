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
        $flux = [
            'APROVED'       => 'PROCESSING',
        ];
        foreach ($list as $order) {
            $manager = $this->factoryManager();
            if (array_key_exists($order->getStatus(), $flux)) {
                $newStatus = $flux[$order->getStatus()];
                $order->setStatus($newStatus);
                $this->assertTrue($manager->saveStatus($order));
                $orderUpdated = $manager->findById($order->getId());

                $this->assertEquals($newStatus, $orderUpdated->getStatus());
            }
        }
    }
    
    /**
     * 
     * @depends testObtemListaPedidos
     */
    public function testAtualizaDadosDeEnvioDeUmPedido($list)
    {
        $flux = [
            'PROCESSING'    => 'SHIPPED',
            //'SHIPPED'       => 'DELIVERED',
        ];
        $this->markTestIncomplete();
        foreach ($list as $order) {
            $manager = $this->factoryManager();
            if (array_key_exists($order->getStatus(), $flux)) {
                $newStatus = $flux[$order->getStatus()];
                $order->setStatus($newStatus);
                $this->assertTrue($manager->saveStatus($order));
                $orderUpdated = $manager->findById($order->getId());

                $this->assertEquals($newStatus, $orderUpdated->getStatus());
            }
        }
    }
}
