<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
        $this->assertSame(1.0, $price->getListPrice());
    }

    public function testPossuiPreçoComDesconto()
    {
        $price = new Price(['listPrice' => '10', 'sellPrice' => 9]);
        $this->assertSame(10.0, $price->getListPrice());
        $this->assertSame(9.0, $price->getSellPrice());
    }
}
