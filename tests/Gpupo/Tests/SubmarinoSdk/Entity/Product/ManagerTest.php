<?php

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\Tests\TestCaseAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityFactory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;

class ManagerTest extends TestCaseAbstract
{    
    public function testGerenciaUpdate()
    {
        $manager = new Manager($this->factoryClient());
     
        foreach($this->dataProviderProducts() as $data) {
            $product = EntityFactory::factory('Product', 'Product', $data);
            $this->assertTrue($manager->save($product));
        }
    }
       
}
