<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

class ManagerTest extends OrderTestCaseAbstract
{
    public function testObtemListaPedidos()
    {
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\CollectionInterface',
            $this->getList());
    }
}
