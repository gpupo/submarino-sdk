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

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class SkuTest extends TestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Sku());
    }

    public function testEnviaDadosOpcionaisApenasSePreenchidos()
    {
        $sku = new Sku([
            'id'   => 1,
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
            $this->assertEquals(1, $sku->get($opcional));
        }
    }

    public function testPossuiPropriedadeContendoUrlDaImagem()
    {
        $url = ['http://foo/bar'];

        $sku = new Sku([
            'id'       => 2,
            'name'     => 'bar',
            'urlImage' => $url,

        ]);

        $this->assertEquals($url, $sku->get('urlImage'));
        $this->assertEquals($url, $sku->getUrlImage());
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
