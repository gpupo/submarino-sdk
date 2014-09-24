<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Sku\Manager;

class ManagerTest extends TestCaseAbstract
{
    public function testListaSkusCadastrados()
    {
        $manager = new Manager($this->factoryClient());

        $list = $manager->fetch();
        
        $this->assertInstanceOf('\Gpupo\CommonSdk\Entity\CollectionInterface', $list);
        
        foreach($list as $item) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $item);    
        }
    }

}
