<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\Tests\CommonSdk\FactoryTestAbstract;
use Gpupo\SubmarinoSdk\Factory;

class FactoryTest extends FactoryTestAbstract
{
    public function getFactory()
    {
        return Factory::getInstance();
    }

    /**
     * @dataProvider dataProviderManager
     */
    public function testCentralizaAcessoAManagers($objectExpected, $target)
    {
        return $this->assertInstanceOf($objectExpected,
            $this->createObject($this->getFactory(),  'factoryManager', $target));
        
    }

    public function dataProviderObjetos()
    {
        return [
            ['\Gpupo\SubmarinoSdk\Entity\Product\Product', 'product', null],
            ['\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', 'sku', null],
            ['\Gpupo\SubmarinoSdk\Entity\Order\Order', 'order', null],
        ];
    }
    
    public function dataProviderManager()
    {
        return [
            ['\Gpupo\SubmarinoSdk\Entity\Product\Manager', 'product'],
            ['\Gpupo\SubmarinoSdk\Entity\Product\Sku\Manager', 'sku'],
            ['\Gpupo\SubmarinoSdk\Entity\Order\Manager', 'order'],
        ];
    }
}
