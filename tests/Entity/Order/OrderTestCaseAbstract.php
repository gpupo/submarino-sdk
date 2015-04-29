<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Common\Entity\Collection;
use Gpupo\SubmarinoSdk\Entity\Order\Manager;
use Gpupo\SubmarinoSdk\Entity\Order\Order;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

abstract class OrderTestCaseAbstract extends TestCaseAbstract
{
    protected function factoryManager()
    {
        return new Manager($this->factoryClient());
    }

    protected function getList()
    {
        /**
          return $this->factoryManager()

            ->setDryRun($this->factoryResponseFromFixture('fixture/Orders.json'))
            ->fetch();
        */

        foreach ($this->dataProviderOrderCollection() as $array) {
            $list[] = current($array);
        }

        return new Collection($list);
    }

    public function dataProviderOrderCollection()
    {
        $list = [];
        foreach ($this->dataProviderOrders() as $order) {
            $list[][] = new Order($order);
        }

        return $list;
    }
}
