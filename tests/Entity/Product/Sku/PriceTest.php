<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Price;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class PriceTest extends TestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Price());
    }

    public function testPossuiPreçoNormal()
    {
        $price = new Price(['listPrice' => '1']);
        $this->assertEquals(1, $price->getListPrice());
    }

    public function testPossuiPreçoComDesconto()
    {
        $price = new Price(['listPrice' => '10', 'sellPrice' => 9]);
        $this->assertEquals(10, $price->getListPrice());
        $this->assertEquals(9, $price->getSellPrice());
    }
}
