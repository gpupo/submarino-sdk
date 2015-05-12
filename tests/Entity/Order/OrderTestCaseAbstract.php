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

use Gpupo\SubmarinoSdk\Entity\Order\Manager;
use Gpupo\SubmarinoSdk\Entity\Order\Order;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

abstract class OrderTestCaseAbstract extends TestCaseAbstract
{
    protected function factoryManager()
    {
        return new Manager($this->factoryClient());
    }

    public function getList()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/list.json');

        return $this->factoryManager()->setDryRun($response)->fetch();
    }

    public function dataProviderOrderCollection()
    {
        $data = [];
        foreach ($this->getList() as $order) {
            $data[] = [$order];
        }

        return $data;
    }

    protected function factoryInterestOrder()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/interest.json');

        return $this->factoryManager()->setDryRun($response)->findById(381264028);
    }
}
