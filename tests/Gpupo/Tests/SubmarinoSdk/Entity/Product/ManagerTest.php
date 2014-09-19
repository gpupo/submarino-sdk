<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;

class ManagerTest extends TestCaseAbstract
{    
    public function testGerenciaUpdate()
    {
        $manager = new Manager($this->factoryClient());
     
        foreach($this->dataProviderProducts() as $data) {
            $product = Factory::factoryProduct($data);
            $this->assertTrue($manager->save($product));
        }
    }
       
}
