<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku;

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
