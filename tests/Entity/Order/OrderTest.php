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
     * @requires extension bcmath
     */
    public function testPossuiValorTotalDeJuros()
    {
        $order = $this->factoryInterestOrder();
        $this->assertEquals(33.58, $order->getTotalAmount());
        $this->assertEquals(7.94, $order->getTotalFreight());
        $this->assertEquals(0, $order->getTotalDiscount());
        $this->assertEquals(0.74, $order->getTotalInterest());

        return $order;
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testPossuiValorTotalDoPedidoDescontadoJuros(Order $order)
    {
        $this->assertEquals('32.84', $order->getTotalReal(), 'Valor total esperado');
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testOTotalRealContémProdutosSomadoAFreteMenosODesconto(Order $order)
    {
        $total = bcadd(24.9, 7.94, 2);
        $this->assertNotEquals($order->getTotalAmount(), $order->getTotalReal());
        $this->assertEquals($total, $order->getTotalReal(), 'Produto mais frete');
    }

    public function testOTotalRealPossuiMesmoValorDeTotalAmountSeNãoHouverJuros()
    {
        $order = $this->factoryInterestOrder();
        $this->assertNotEquals($order->getTotalAmount(), $order->getTotalReal());
        $order->setTotalInterest(0);
        $this->assertEquals($order->getTotalAmount(), $order->getTotalReal());
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testOTotalRealContémTotalMenosJuros(Order $order)
    {
        $this->assertEquals(bcsub(33.58, 0.74, 2), $order->getTotalReal(), 'Valor total menos o juros');
    }
}
