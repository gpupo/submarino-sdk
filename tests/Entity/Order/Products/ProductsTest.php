<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Products;

use Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Product;
use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class ProductsTest extends OrderTestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Product());
    }

    public function testCadaPedidoPossuiUmaColeçãoDeObjetosProduto()
    {
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
