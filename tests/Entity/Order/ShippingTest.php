<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Tests\Traits\EntityTrait;

/**
 * @coversNothing
 */
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
            'shippingEstimateId' => 'string',
            'shippingMethodId' => 'string',
            'shippingMethodName' => 'string',
            'calculationType' => 'string',
            'shippingMethodDisplayName' => 'string',
        ];

        return $this->dataProviderEntitySchema(self::QUALIFIED, $expected);
    }

    /**
     * @testdox Possui método ``getShippingEstimateId()`` para acessar ShippingEstimateId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingEstimateId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingEstimateId()`` que define ShippingEstimateId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterSiteId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingEstimateId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodId()`` para acessar ShippingMethodId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterShippingMethodId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodId', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodId()`` que define ShippingMethodId
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterShippingMethodId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodId', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodName()`` para acessar ShippingMethodName
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterShippingMethodName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodName', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodName()`` que define ShippingMethodName
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterShippingMethodName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodName', 'string', $object);
    }

    /**
     * @testdox Possui método ``getCalculationType()`` para acessar CalculationType
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterCalculationType(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('calculationType', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setCalculationType()`` que define CalculationType
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterCalculationType(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('calculationType', 'string', $object);
    }

    /**
     * @testdox Possui método ``getShippingMethodDisplayName()`` para acessar ShippingMethodDisplayName
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterShippingMethodDisplayName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('shippingMethodDisplayName', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setShippingMethodDisplayName()`` que define ShippingMethodDisplayName
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterShippingMethodDisplayName(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('shippingMethodDisplayName', 'string', $object);
    }
}
