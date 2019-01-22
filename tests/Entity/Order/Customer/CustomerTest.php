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

namespace Gpupo\SubmarinoSdk\Tests\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Tests\Traits\EntityTrait;
use Gpupo\SubmarinoSdk\Entity\Order\Customer\Customer;
use Gpupo\SubmarinoSdk\Tests\Entity\Order\OrderTestCaseAbstract;

/**
 * @coversNothing
 */
class CustomerTest extends OrderTestCaseAbstract
{
    use EntityTrait;

    const QUALIFIED = Customer::class;

    public static function setUpBeforeClass()
    {
        static::setFullyQualifiedObject(self::QUALIFIED);
        parent::setUpBeforeClass();
    }

    public function dataProviderObject()
    {
        $expected = [
            'pf' => [],
            'pj' => [],
            'telephones' => [],
            'deliveryAddress' => [],
        ];

        return $this->dataProviderEntitySchema(self::QUALIFIED, $expected);
    }

    public function testCadaClientePossuiEnderecoDeEntregaComoObjeto()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\DeliveryAddress',
            $order->getCustomer()->getDeliveryAddress()
            );
        }
    }

    public function testCadaClientePossuiColecaoDeTelefones()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getCustomer()->getTelephones()
            );
        }
    }

    public function testCadaClientePossuiObjetoPessoaFisica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pf',
            $order->getCustomer()->getPf()
            );
        }
    }

    public function testCadaClientePossuiObjetoPessoaJuridica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pj',
            $order->getCustomer()->getPj()
            );
        }
    }

    /**
     * @testdox Possui método ``getPf()`` para acessar Pf
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPf(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('pf', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPf()`` que define Pf
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPf(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('pf', 'object', $object);
    }

    /**
     * @testdox Possui método ``getPj()`` para acessar Pj
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterPj(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('pj', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setPj()`` que define Pj
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterPj(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('pj', 'object', $object);
    }

    /**
     * @testdox Possui método ``getTelephones()`` para acessar Telephones
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterTelephones(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('telephones', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setTelephones()`` que define Telephones
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterTelephones(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('telephones', 'object', $object);
    }

    /**
     * @testdox Possui método ``getDeliveryAddress()`` para acessar DeliveryAddress
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testGetterDeliveryAddress(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaGetter('deliveryAddress', 'object', $object, $expected);
    }

    /**
     * @testdox Possui método ``setDeliveryAddress()`` que define DeliveryAddress
     * @dataProvider dataProviderObject
     *
     * @param null|mixed $expected
     */
    public function testSetterDeliveryAddress(EntityInterface $object, $expected = null)
    {
        $this->assertSchemaSetter('deliveryAddress', 'object', $object);
    }
}
