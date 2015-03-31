<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;
use Gpupo\Tests\TestCaseAbstract;

class SkuTest extends TestCaseAbstract
{
    public function testEnviaDadosOpcionaisApenasSePreenchidos()
    {
        $sku = new Sku([
            'id'    => 1,
            'name'  => 'foo',
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
}
