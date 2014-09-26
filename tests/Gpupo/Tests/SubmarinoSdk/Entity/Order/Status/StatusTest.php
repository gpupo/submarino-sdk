<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Status;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Order\Order;

class StatusTest extends OrderTestCaseAbstract
{
    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testCadaStatusPossuiObjetoShipped(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Shipped', $status->getShipped());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testCadaStatusPossuiObjetoDelivered(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Delivered', $status->getDelivered());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoRemetidoSemPossuirObjetoShippedValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('SHIPPED');
        $status->toJson();
    }
}
