<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\SubmarinoSdk\Entity\Order\Order;

class OrderTest extends OrderTestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Order());
    }

    public function testCadaItemDeUmaListaEUmObjeto()
    {
        $list = $this->getList();

        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Order', $item);
        }

        return $list;
    }

    /**
     * @depends testCadaItemDeUmaListaEUmObjeto
     */
    public function testCadaPedidoPossuiObjetoCliente(CollectionInterface $list)
    {
        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Customer', $item->getCustomer());
        }
    }

    /**
     * @depends testCadaItemDeUmaListaEUmObjeto
     */
    public function testCadaPedidoPossuiObjetoComDadosDeCobrança(CollectionInterface $list)
    {
        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Payer\Payer', $item->getPayer());
        }
    }

    /**
     * @depends testCadaItemDeUmaListaEUmObjeto
     */
    public function testCadaPedidoPossuiColecaoDeProdutos(CollectionInterface $list)
    {
        foreach ($list as $item) {
            $collection = $item->getProducts();

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Products', $collection);

            foreach ($collection as $product) {
                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Product', $product);
            }
        }
    }

    /**
     * @dataProvider dataProviderOrderCollection
     */
    public function testCadaPedidoPossuiObjetoStatus(Order $order)
    {
        $status =  $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Status', $status);
        $this->assertArrayHasKey('status', $order->toStatus());
    }

    public function testPossuiLojaDeOrigem()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/detail.json');

        $order = $this->factoryManager()->setDryRun($response)->findById(589);

        $this->assertEquals('SUBMARINO', $order->getStore());

        return $order;
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDoPedido(Order $order)
    {
        $this->assertEquals(133.41, $order->getTotalAmount());
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDoFrete(Order $order)
    {
        $this->assertEquals(40, $order->getTotalFreight());
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDeDesconto(Order $order)
    {
        $this->assertEquals(4, $order->getTotalDiscount());
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDeJuros(Order $order)
    {
        $this->assertEquals(2, $order->getTotalInterest());
    }
}
