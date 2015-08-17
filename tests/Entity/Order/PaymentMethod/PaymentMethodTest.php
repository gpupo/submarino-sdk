<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\PaymentMethods;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\Tests\CommonSdk\Traits\EntityTrait;
use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class PaymentMethodTest extends OrderTestCaseAbstract
{
    use EntityTrait;

    const QUALIFIED = 'Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethod';

    public static function setUpBeforeClass()
    {
        static::setFullyQualifiedObject(self::QUALIFIED);
        parent::setUpBeforeClass();
    }

    public function dataProviderObject()
    {
        $expected = [
            'sequential' => 2,
            'id'         => 'string',
            'value'      => 12.99,
        ];

        return $this->dataProviderEntitySchema(self::QUALIFIED, $expected);
    }

    public function testCadaPedidoPossuiUmaColeçãoDeObjetosPaymentMethod()
    {
        foreach ($this->getList() as $order) {
            foreach ($order->getPaymentMethods() as $paymentMethod) {
                $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethod',
                $paymentMethod);
            }
        }
    }

    /**
     * @testdox Possui método ``getSequential()`` para acessar Sequential
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterSequential(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('sequential', 'integer', $object, $expected);
    }

    /**
     * @testdox Possui método ``setSequential()`` que define Sequential
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterSequential(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('sequential', 'integer', $object);
    }

    /**
     * @testdox Possui método ``getId()`` para acessar Id
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('id', 'string', $object, $expected);
    }

    /**
     * @testdox Possui método ``setId()`` que define Id
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterId(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('id', 'string', $object);
    }

    /**
     * @testdox Possui método ``getValue()`` para acessar Value
     * @dataProvider dataProviderObject
     * @test
     */
    public function getterValue(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('value', 'number', $object, $expected);
    }

    /**
     * @testdox Possui método ``setValue()`` que define Value
     * @dataProvider dataProviderObject
     * @test
     */
    public function setterValue(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('value', 'number', $object);
    }
}
