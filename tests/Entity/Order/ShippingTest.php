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

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\Tests\CommonSdk\Traits\EntityTrait;

class ShippingTest extends OrderTestCaseAbstract
{
    use EntityTrait;

    const QUALIFIED = 'Gpupo\SubmarinoSdk\Entity\Order\Shipping';

    public static function setUpBeforeClass()
    {
        static::setFullyQualifiedObject(self::QUALIFIED);
        parent::setUpBeforeClass();
    }

    public function dataProviderObject()
    {
        $expected = [
            'shippingEstimateId'        => 'string',
            'shippingMethodId'          => 'string',
            'shippingMethodName'        => 'string',
            'calculationType'           => 'string',
            'shippingMethodDisplayName' => 'string',
        ];

        return $this->dataProviderEntitySchema(self::QUALIFIED, $expected);
    }

    /**
     * @testdox Possui método ``getShippingEstimateId()`` para acessar ShippingEstimateId
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingEstimateId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingEstimateId()`` que define ShippingEstimateId
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingEstimateId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodId()`` para acessar ShippingMethodId
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterShippingMethodId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodId()`` que define ShippingMethodId
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterShippingMethodId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodName()`` para acessar ShippingMethodName
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterShippingMethodName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodName', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodName()`` que define ShippingMethodName
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterShippingMethodName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodName', 'string', $object);
    }

    /**
     * @testdox Possui método ``getCalculationType()`` para acessar CalculationType
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterCalculationType(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('calculationType', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setCalculationType()`` que define CalculationType
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterCalculationType(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('calculationType', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodDisplayName()`` para acessar ShippingMethodDisplayName
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterShippingMethodDisplayName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodDisplayName', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodDisplayName()`` que define ShippingMethodDisplayName
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterShippingMethodDisplayName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodDisplayName', 'string', $object);
    }
}
