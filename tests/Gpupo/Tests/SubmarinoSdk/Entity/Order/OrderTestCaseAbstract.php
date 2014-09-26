<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Order\Manager;
use Gpupo\SubmarinoSdk\Entity\Order\Order;

abstract class OrderTestCaseAbstract extends TestCaseAbstract
{
    protected function factoryManager()
    {
        return new Manager($this->factoryClient());
    }
    
    protected function getList()
    {
        return $this->factoryManager()->fetch();
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
