<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

class OrderTest extends OrderTestCaseAbstract
{ 
    public function testCadaItemDeUmaListaEUmObjeto()
    {
        foreach ($this->getList() as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Order', $item);
        }
    }
    
    public function testCadaPedidoPossuiObjetoCliente()
    {
        foreach ($this->getList() as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Customer', $item->getCustomer());
        }
    }

    public function testCadaPedidoPossuiColecaoDeProdutos()
    {
        foreach ($this->getList() as $item) {
            $collection = $item->getProducts();
                        
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Products', $collection);
            
            foreach ($collection as $product) {
                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Product', $product);
            }
        }
    }
}
