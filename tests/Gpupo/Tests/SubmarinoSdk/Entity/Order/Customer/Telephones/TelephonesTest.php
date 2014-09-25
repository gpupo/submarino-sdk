<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer\Telephones;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class CustomerTest extends OrderTestCaseAbstract
{
    public function testCadaClientePossuiColecaoDeTelefones()
    {
        foreach($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getCustomer()->getTelephones());
            
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Main',
            $order->getCustomer()->getTelephones()->getMain());
            
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Secondary',
            $order->getCustomer()->getTelephones()->getSecondary());
            
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Business',
            $order->getCustomer()->getTelephones()->getBusiness());
        }
    }
 
}
