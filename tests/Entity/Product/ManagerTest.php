<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Tests\Entity\Product;

use Gpupo\Common\Entity\CollectionInterface;
// use Gpupo\CommonSchema\ORM\Entity\Catalog\Product\Product;
use Gpupo\CommonSchema\ArrayCollection\Catalog\Product\Product;
// use Gpupo\SubmarinoSdk\Entity\Product\Product;
use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;
use Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;

/**
 * @coversNothing
 */
class ManagerTest extends TestCaseAbstract
{
    public function testGetProducts()
    {
        $response = $this->factoryResponseFromFixture('mockup/products/list.json');
        $list = $this->getManager($response)->fetch();
        $this->assertInstanceOf(CollectionInterface::class, $list);
        $this->assertInstanceOf(MetadataContainer::class, $list);
        foreach ($list as $product) {
            $this->assertInstanceOf(Product::class, $product);
        }
    }

    /**
     * @testdox É um Product de CommonSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testIsProductObject()
    {
        $product = $this->factoryDetail();
        $this->assertInstanceOf(Product::class, $product);
    }

    public function testGerenciaUpdate()
    {
        $product = $this->factoryDetail();
        $manager = $this->getManager();
        $this->assertTrue($manager->save($product));
    }

    protected function getManager($response = null)
    {
        return $this->getFactory()->factoryManager('product')->setDryRun($response);
    }

    protected function factoryDetail()
    {
        $response = $this->factoryResponseFromFixture('mockup/products/detail.json');

        return $this->getManager($response)->findById(9474);
    }
}
