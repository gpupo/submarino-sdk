<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Order\Manager;

abstract class OrderTestCaseAbstract extends TestCaseAbstract
{
    protected function getList()
    {
        $manager = new Manager($this->factoryClient());

        return $manager->fetch();
    }
}
