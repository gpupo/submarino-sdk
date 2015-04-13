<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class CustomerTest extends OrderTestCaseAbstract
{
    public function testCadaClientePossuiEnderecoDeEntregaComoObjeto()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\DeliveryAddress',
            $order->getCustomer()->getDeliveryAddress());
        }
    }

    public function testCadaClientePossuiColecaoDeTelefones()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getCustomer()->getTelephones());
        }
    }

    public function testCadaClientePossuiObjetoPessoaFisica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pf',
            $order->getCustomer()->getPf());
        }
    }

    public function testCadaClientePossuiObjetoPessoaJuridica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pj',
            $order->getCustomer()->getPj());
        }
    }
}
