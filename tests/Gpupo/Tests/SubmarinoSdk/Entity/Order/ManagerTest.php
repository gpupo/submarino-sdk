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
        foreach ($list as $order) {
            $manager = $this->factoryManager();
            $order->setStatus('PROCESSING');
            $manager->saveStatus($order);
        }
    }
}
