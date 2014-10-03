<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Products;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class ProductsTest extends OrderTestCaseAbstract
{
    public function testCadaProdutoEUmObjeto()
    {
        if (!$this->hasToken()) {
            return $this->markTestIncomplete('API Token ausente');
        }

        foreach ($this->getList() as $order) {
            foreach ($order->getProducts() as $product) {
                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Product',
                $product);

                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Link',
                $product->getLink());
            }
        }
    }
}
