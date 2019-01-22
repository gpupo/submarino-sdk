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

namespace Gpupo\SubmarinoSdk\Tests\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class SkuTest extends TestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Sku());
    }

    public function testEnviaDadosOpcionaisApenasSePreenchidos()
    {
        $sku = new Sku([
            'id' => 1,
            'name' => 'foo',
        ]);

        $opcionais = [
            'height',
            'width',
            'length',
        ];

        foreach ($opcionais as $opcional) {
            $this->assertArrayNotHasKey($opcional, $sku->toArray(), $sku);
            $sku->set($opcional, 1);
            $this->assertArrayHasKey($opcional, $sku->toArray(), $sku);
            $this->assertSame(1, $sku->get($opcional));
        }
    }

    public function testPossuiPropriedadeContendoUrlDaImagem()
    {
        $url = ['http://foo/bar'];

        $sku = new Sku([
            'id' => 2,
            'name' => 'bar',
            'urlImage' => $url,
        ]);

        $this->assertSame($url, $sku->get('urlImage'));
        $this->assertSame($url, $sku->getUrlImage());
    }

    public function testSkuPossuiObjetoStatus()
    {
        $sku = new Sku();
        $sku->setEnable(true);

        $this->assertJson($sku->toJson('status'));
        $this->assertJsonStringEqualsJsonString('{"enable":true}', $sku->toJson('status'));
    }

    public function testSkuPossuiObjetoStock()
    {
        $sku = new Sku();
        $sku->setStockQuantity(500);

        $this->assertJson($sku->toJson('stock'));
        $this->assertJsonStringEqualsJsonString('{"quantity":500}', $sku->toJson('stock'));
    }
}
