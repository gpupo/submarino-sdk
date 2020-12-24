<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Tests;

use Gpupo\CommonSchema\ORM\Entity\Catalog\Product\Product;
use Gpupo\CommonSchema\ORM\Entity\Trading\Order\Order;
use Gpupo\CommonSdk\Tests\FactoryTestAbstract;
use Gpupo\SubmarinoSdk\Factory;

/**
 * @coversNothing
 */
class FactoryTest extends FactoryTestAbstract
{
    public function getFactory()
    {
        return Factory::getInstance();
    }

    /**
     * @dataProvider dataProviderManager
     *
     * @param mixed $objectExpected
     * @param mixed $target
     */
    public function testCentralizaAcessoAManagers($objectExpected, $target)
    {
        return $this->assertInstanceOf(
            $objectExpected,
            $this->createObject($this->getFactory(), 'factoryManager', $target)
        );
    }

    public function testSetApplicationApiClient()
    {
        $factory = $this->getFactory();

        $this->assertSame($factory->getOptions()->get('user_email'), $factory->getClient()->getOptions()->get('user_email'), 'Primal values');
    }

    public function dataProviderObjetos()
    {
        return [
            [Product::class, 'product', null],
            [Order::class, 'order', null],
        ];
    }

    public function dataProviderManager()
    {
        return [
            ['\Gpupo\SubmarinoSdk\Entity\Product\Manager', 'product'],
            ['\Gpupo\SubmarinoSdk\Entity\Order\Manager', 'order'],
        ];
    }
}
