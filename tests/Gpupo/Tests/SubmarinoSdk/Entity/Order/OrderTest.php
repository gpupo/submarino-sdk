<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\CollectionInterface;
use Gpupo\SubmarinoSdk\Entity\Order\Order;

class OrderTest extends OrderTestCaseAbstract
{
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
    }

}
