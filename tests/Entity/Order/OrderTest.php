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

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Tests\Traits\EntityTrait;
use Gpupo\SubmarinoSdk\Entity\Order\Order;

/**
 * @coversNothing
 */
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
            'id' => 'string',
            'siteId' => 'string',
            'store' => 'string',
            'purchaseDate' => 'string',
            'lastUpdate' => 'string',
            'purchaseTimestamp' => 'string',
            'lastUpdateTimestamp' => 'string',
            'status' => [],
            'invoiced' => [],
            'estimatedDeliveryDate' => 'string',
            'customer' => [],
            'payer' => [],
            'totalAmount' => 1.1,
            'totalFreight' => 2.2,
            'totalDiscount' => 0.1,
            'totalInterest' => 1.2,
            'products' => [],
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
        $status = $order->getStatus();
        $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Status\Status', $status);
        $this->assertArrayHasKey('status', $order->toStatus());
    }

    public function testPossuiLojaDeOrigem()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/detail.json');

        $order = $this->factoryManager()->setDryRun($response)->findById(589);

        $this->assertSame('SUBMARINO', $order->getStore());

        return $order;
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDoPedido(Order $order)
    {
        $this->assertSame(133.41, $order->getTotalAmount());
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDoFrete(Order $order)
    {
        $this->assertSame(40.0, $order->getTotalFreight());
    }

    /**
     * @depends testPossuiLojaDeOrigem
     */
    public function testPossuiValorTotalDeDesconto(Order $order)
    {
        $this->assertSame(4.0, $order->getTotalDiscount());
    }

    /**
     * @requires extension bcmath
     */
    public function testPossuiValorTotalDeJuros()
    {
        $order = $this->factoryInterestOrder();
        $this->assertSame(33.58, $order->getTotalAmount());
        $this->assertSame(7.94, $order->getTotalFreight());
        $this->assertSame(0.0, $order->getTotalDiscount());
        $this->assertSame(0.74, $order->getTotalInterest());

        return $order;
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testPossuiValorTotalDoPedidoDescontadoJuros(Order $order)
    {
        $this->assertSame('32.84', $order->getTotalReal(), 'Valor total esperado');
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testOTotalRealContémProdutosSomadoAFreteMenosODesconto(Order $order)
    {
        $total = bcadd(24.9, 7.94, 2);
        $this->assertNotSame($order->getTotalAmount(), $order->getTotalReal());
        $this->assertSame($total, $order->getTotalReal(), 'Produto mais frete');
    }

    public function testOTotalRealPossuiMesmoValorDeTotalAmountSeNãoHouverJuros()
    {
        $order = $this->factoryInterestOrder();
        $this->assertNotSame($order->getTotalAmount(), $order->getTotalReal());
        $order->setTotalInterest(0);
        $this->assertSame((float) $order->getTotalAmount(), (float) $order->getTotalReal());
    }

    /**
     * @depends testPossuiValorTotalDeJuros
     */
    public function testOTotalRealContémTotalMenosJuros(Order $order)
    {
        $this->assertSame(bcsub(33.58, 0.74, 2), $order->getTotalReal(), 'Valor total menos o juros');
    }

    /**
     * @testdox Possui método ``setId()`` que define Id
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('id', 'string', $object);
    }

    /**
     * @testdox Possui método ``getSiteId()`` para acessar SiteId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('siteId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setSiteId()`` que define SiteId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('siteId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getStore()`` para acessar Store
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterStore(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('store', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setStore()`` que define Store
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterStore(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('store', 'string', $object);
    }

    /**
     * @testdox Possui método ``getPurchaseDate()`` para acessar PurchaseDate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPurchaseDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('purchaseDate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPurchaseDate()`` que define PurchaseDate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPurchaseDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('purchaseDate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getLastUpdate()`` para acessar LastUpdate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterLastUpdate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('lastUpdate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setLastUpdate()`` que define LastUpdate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterLastUpdate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('lastUpdate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getPurchaseTimestamp()`` para acessar PurchaseTimestamp
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPurchaseTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('purchaseTimestamp', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPurchaseTimestamp()`` que define PurchaseTimestamp
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPurchaseTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('purchaseTimestamp', 'string', $object);
    }

    /**
     * @testdox Possui método ``getLastUpdateTimestamp()`` para acessar LastUpdateTimestamp
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterLastUpdateTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('lastUpdateTimestamp', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setLastUpdateTimestamp()`` que define LastUpdateTimestamp
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterLastUpdateTimestamp(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('lastUpdateTimestamp', 'string', $object);
    }

    /**
     * @testdox Possui método ``getStatus()`` para acessar Status
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterStatus(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('status', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setStatus()`` que define Status
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterStatus(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('status', 'object', $object);
    }

    /**
     * @testdox Possui método ``getInvoiced()`` para acessar Invoiced
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterInvoiced(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('invoiced', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setInvoiced()`` que define Invoiced
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterInvoiced(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('invoiced', 'object', $object);
    }

    /**
     * @testdox Possui método ``getEstimatedDeliveryDate()`` para acessar EstimatedDeliveryDate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterEstimatedDeliveryDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('estimatedDeliveryDate', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setEstimatedDeliveryDate()`` que define EstimatedDeliveryDate
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterEstimatedDeliveryDate(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('estimatedDeliveryDate', 'string', $object);
    }

    /**
     * @testdox Possui método ``getCustomer()`` para acessar Customer
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterCustomer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('customer', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setCustomer()`` que define Customer
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterCustomer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('customer', 'object', $object);
    }

    /**
     * @testdox Possui método ``getPayer()`` para acessar Payer
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPayer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('payer', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPayer()`` que define Payer
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPayer(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('payer', 'object', $object);
    }

    /**
     * @testdox Possui método ``getTotalAmount()`` para acessar TotalAmount
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterTotalAmount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalAmount', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalAmount()`` que define TotalAmount
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterTotalAmount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalAmount', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalFreight()`` para acessar TotalFreight
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterTotalFreight(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalFreight', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalFreight()`` que define TotalFreight
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterTotalFreight(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalFreight', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalDiscount()`` para acessar TotalDiscount
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterTotalDiscount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalDiscount', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalDiscount()`` que define TotalDiscount
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterTotalDiscount(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalDiscount', 'number', $object);
    }

    /**
     * @testdox Possui método ``getTotalInterest()`` para acessar TotalInterest
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterTotalInterest(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('totalInterest', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTotalInterest()`` que define TotalInterest
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterTotalInterest(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('totalInterest', 'number', $object);
    }

    /**
     * @testdox Possui método ``getProducts()`` para acessar Products
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterProducts(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('products', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setProducts()`` que define Products
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterProducts(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('products', 'object', $object);
    }

    /**
     * @testdox Possui método ``getShipping()`` para acessar Shipping
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterShipping(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shipping', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShipping()`` que define Shipping
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterShipping(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shipping', 'object', $object);
    }

    /**
     * @testdox Possui método ``getPaymentMethods()`` para acessar PaymentMethods
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPaymentMethods(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('paymentMethods', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPaymentMethods()`` que define PaymentMethods
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPaymentMethods(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('paymentMethods', 'object', $object);
    }
}
