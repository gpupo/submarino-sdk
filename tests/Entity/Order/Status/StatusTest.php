<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Status;

use Gpupo\SubmarinoSdk\Entity\Order\Order;
use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class StatusTest extends OrderTestCaseAbstract
{
    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testCadaStatusPodeSerImpressoComoString(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertContains((string) $status, ['PROCESSING', 'DELIVERED']);
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testCadaStatusPossuiObjetoInvoiced(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Invoiced', $status->getInvoiced());
    }

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
    public function testCadaStatusPossuiObjetoShipmentException(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\ShipmentException', $status->getShipmentException());
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
     */
    public function testCadaStatusPossuiObjetoUnavailable(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Unavailable', $status->getUnavailable());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoFaturadoSemPossuirObjetoInvoicedValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('INVOICED');
        $this->assertFalse($status->isValid());
        echo $status->toJson();
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testSucessoAoMarcarComoFaturadoInformandoObjetoInvoicedValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('INVOICED');
        $status->getInvoiced()
            ->setNumber(123456789012)
            ->setLine(123)
            ->setIssueDate('2014-12-01 10:00:00')
            ->setKey(10293847561029384756102938475610293847561029)
            ->setDanfeXml('<xml></xml>');
        $this->assertTrue($status->isValid());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoRemetidoSemPossuirObjetoShippedValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('SHIPPED');
        $this->assertFalse($status->isValid());
        echo $status->toJson();
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testSucessoAoMarcarComoRemetidoInformandoObjetoShippedValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('SHIPPED');
        $status->getShipped()->setEstimatedDelivery('2014-12-01 10:00:00')
            ->setDeliveredCarrierDate(date('Y-m-d H:i:s'));
        $this->assertTrue($status->isValid());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoFalhaNaEntregaSemPossuirObjetoShipmentExceptionValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('SHIPPED');
        $status->getShipmentException()->setRequired();
        $this->assertFalse($status->isValid());
        echo $status->toJson();
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testSucessoAoMarcarComoFalhaNaEntregaInformandoObjetoShipmentExceptionValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('SHIPPED');
        $status->getShipmentException()->setRequired();
        $status->getShipmentException()
            ->setOccurrenceDate('2014-12-01 10:00:00')
            ->setObservation('Observation field filled here');
        $this->assertFalse($status->isValid());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoEntregueSemPossuirObjetoDeliveredValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('DELIVERED');
        $this->assertFalse($status->isValid());
        echo $status->toJson();
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testSucessoAoMarcarComoEntregueInformandoObjetoDeliveredValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('DELIVERED');
        $status->getDelivered()->setDeliveredCustomerDate(date('Y-m-d H:i:s'));
        $this->assertTrue($status->isValid());
    }

    /**
     * @dataProvider dataProviderOrderCollection
     * @expectedException \Gpupo\CommonSdk\Exception\ExceptionInterface
     */
    public function testFalhaAoMarcarComoIndisponivelSemPossuirObjetoUnavailableValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('UNAVAILABLE');
        $this->assertFalse($status->isValid());
        echo $status->toJson();
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testSucessoAoMarcarComoIndisponivelInformandoObjetoUnavailableValido(Order $order)
    {
        $status =  $order->getStatus();
        $status->setStatus('UNAVAILABLE');
        $status->getUnavailable()
            ->setUnavailableDate(date('Y-m-d H:i:s'))
            ->setObservation('Observation field filled here');
        $this->assertTrue($status->isValid());
    }
}
