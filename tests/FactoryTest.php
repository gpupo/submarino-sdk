<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\SubmarinoSdk\Factory;
use Gpupo\Tests\CommonSdk\FactoryTestAbstract;

class FactoryTest extends FactoryTestAbstract
{
    public $namespace =  '\Gpupo\SubmarinoSdk\\';

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
            $this->createObject($this->getFactory(), 'factoryManager', $target));
    }

    public function dataProviderObjetos()
    {
        return [
            [$this->namespace.'Entity\Product\Product', 'product', null],
            [$this->namespace.'Entity\Product\Sku\Sku', 'sku', null],
            [$this->namespace.'Entity\Order\Order', 'order', null],
        ];
    }

    public function dataProviderManager()
    {
        return [
            [$this->namespace.'Entity\Product\Manager', 'product'],
            [$this->namespace.'Entity\Product\Sku\Manager', 'sku'],
            [$this->namespace.'Entity\Order\Manager', 'order'],
        ];
    }
}
