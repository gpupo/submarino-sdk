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
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\Order\Order;
use Gpupo\Tests\CommonSdk\Traits\EntityTrait;

class OrderTest extends OrderTestCaseAbstract
{
    use EntityTrait;

    const QUALIFIED = 'Gpupo\SubmarinoSdk\Entity\Order\Order';

    public static function setUpBeforeClass()
    {
        static::setFullyQualifiedObject(self::QUALIFIED);
        parent::setUpBeforeClass();
    }

    public function dataProviderObject()
    {
        $expected = [
            'id'                    => 'string',
            'siteId'                => 'string',
            'store'                 => 'string',
            'purchaseDate'          => 'string',
            'lastUpdate'            => 'string',
            'purchaseTimestamp'     => 'string',
            'lastUpdateTimestamp'   => 'string',
            'status'                => [],
            'invoiced'              => [],
            'estimatedDeliveryDate' => 'string',
            'customer'              => [],
            'payer'                 => [],
            'totalAmount'           => 1.1,
            'totalFreight'          => 2.2,
            'totalDiscount'         => 0.1,
            'totalInterest'         => 1.2,
            'products'              => [],
        ];

        return $this->dataProviderEntitySchema(self::QUALIFIED, $expected);
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
     * @depends testCadaItemDeUmaListaEUmObjeto
     */
    public function testCadaPedidoPossuiColecaoDeMetodosDePagamento(CollectionInterface $list)
    {
        foreach ($list as $item) {
            $collection = $item->getPaymentMethods();

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethods', $collection);

            foreach ($collection as $product) {
                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethod', $product);
            }
        }
    }

    /**
     * @depends testCadaItemDeUmaListaEUmObjeto
     */
    public function testCadaPedidoPossuiObjetoComDadosDeEntrega(CollectionInterface $list)
    {
        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Shipping', $item->getShipping());
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

    /**
     * @testdox Possui método ``setId()`` que define Id
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('id', 'string', $object);
    }

    /**
     * @testdox Possui método ``getSiteId()`` para acessar SiteId
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('siteId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setSiteId()`` que define SiteId
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('siteId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getStore()`` para acessar Store
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterStore(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('store', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setStore()`` que define Store
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterStore(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('store', 'string', $object);
    }

    /**
     * @testdox Possui método ``getPurchaseDate()`` para acessar PurchaseDate
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterPurchaseDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('purchaseDate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPurchaseDate()`` que define PurchaseDate
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterPurchaseDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('purchaseDate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getLastUpdate()`` para acessar LastUpdate
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterLastUpdate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('lastUpdate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setLastUpdate()`` que define LastUpdate
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterLastUpdate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('lastUpdate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getPurchaseTimestamp()`` para acessar PurchaseTimestamp
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterPurchaseTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('purchaseTimestamp', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPurchaseTimestamp()`` que define PurchaseTimestamp
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterPurchaseTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('purchaseTimestamp', 'string', $object);
    }

    /**
     * @testdox Possui método ``getLastUpdateTimestamp()`` para acessar LastUpdateTimestamp
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterLastUpdateTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('lastUpdateTimestamp', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setLastUpdateTimestamp()`` que define LastUpdateTimestamp
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterLastUpdateTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('lastUpdateTimestamp', 'string', $object);
    }

    /**
     * @testdox Possui método ``getStatus()`` para acessar Status
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterStatus(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('status', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setStatus()`` que define Status
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterStatus(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('status', 'object', $object);
    }

    /**
     * @testdox Possui método ``getInvoiced()`` para acessar Invoiced
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterInvoiced(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('invoiced', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setInvoiced()`` que define Invoiced
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterInvoiced(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('invoiced', 'object', $object);
    }

    /**
     * @testdox Possui método ``getEstimatedDeliveryDate()`` para acessar EstimatedDeliveryDate
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterEstimatedDeliveryDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('estimatedDeliveryDate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setEstimatedDeliveryDate()`` que define EstimatedDeliveryDate
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterEstimatedDeliveryDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('estimatedDeliveryDate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getCustomer()`` para acessar Customer
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterCustomer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('customer', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setCustomer()`` que define Customer
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterCustomer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('customer', 'object', $object);
    }

    /**
     * @testdox Possui método ``getPayer()`` para acessar Payer
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterPayer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('payer', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPayer()`` que define Payer
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterPayer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('payer', 'object', $object);
    }

    /**
     * @testdox Possui método ``getTotalAmount()`` para acessar TotalAmount
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterTotalAmount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalAmount', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalAmount()`` que define TotalAmount
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterTotalAmount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalAmount', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalFreight()`` para acessar TotalFreight
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterTotalFreight(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalFreight', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalFreight()`` que define TotalFreight
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterTotalFreight(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalFreight', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalDiscount()`` para acessar TotalDiscount
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterTotalDiscount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalDiscount', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalDiscount()`` que define TotalDiscount
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterTotalDiscount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalDiscount', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalInterest()`` para acessar TotalInterest
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterTotalInterest(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalInterest', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalInterest()`` que define TotalInterest
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterTotalInterest(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalInterest', 'number', $object);
    }

    /**
     * @testdox Possui método ``getProducts()`` para acessar Products
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterProducts(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('products', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setProducts()`` que define Products
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterProducts(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('products', 'object', $object);
    }

    /**
     * @testdox Possui método ``getShipping()`` para acessar Shipping
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterShipping(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shipping', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShipping()`` que define Shipping
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterShipping(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shipping', 'object', $object);
    }

    /**
     * @testdox Possui método ``getPaymentMethods()`` para acessar PaymentMethods
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterPaymentMethods(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('paymentMethods', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPaymentMethods()`` que define PaymentMethods
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterPaymentMethods(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('paymentMethods', 'object', $object);
    }
}
