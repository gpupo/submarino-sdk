<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Order\Factory;
use Gpupo\SubmarinoSdk\Entity\Order\Manager;

class ManagerTest extends TestCaseAbstract
{
    public function testListaPedidos()
    {
        $manager = new Manager($this->factoryClient());

        $list = $manager->fetch();
        
        print_r($list);

        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\CollectionInterface', $list);

        foreach ($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Order', $item);
        }
    }

}
