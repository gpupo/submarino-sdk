<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Tests;

use Gpupo\CommonSdk\Tests\TestCaseAbstract as Core;
use Gpupo\SubmarinoSdk\Factory;

abstract class TestCaseAbstract extends Core
{
    private $factory;

    public static function getResourcesPath()
    {
        return \dirname(__DIR__).'/Resources/';
    }

    public function factoryClient()
    {
        return $this->getFactory()->getClient();
    }

    public function providerProducts()
    {
        $manager = $this->getFactory()->factoryManager('product');
        $manager->setDryRun($this->factoryResponseFromFixture('mockup/products/list.json'));

        return $manager->fetch();
    }

    public function dataProviderProducts()
    {
        $list = [];

        foreach ($this->providerProducts() as $product) {
            $list[] = [$product];
        }

        return $list;
    }

    public function dataProviderOrders()
    {
        return $this->getResourceJson('mockup/orders/list.json');
    }

    protected function getOptions()
    {
        return getenv();
    }

    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = Factory::getInstance()->setup($this->getOptions(), $this->getLogger());
        }

        return $this->factory;
    }
}
