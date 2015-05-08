<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Payer;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class PayerTest extends OrderTestCaseAbstract
{
    public function testCadaPagadorPossuiEnderecoDeCobrançaComoObjeto()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\BillingAddress',
            $order->getPayer()->getBillingAddress());
        }
    }

    public function testCadaPagadorPossuiColecaoDeTelefones()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getPayer()->getTelephones());
        }
    }

    public function testCadaPagadorPossuiObjetoPessoaFisica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pf',
            $order->getPayer()->getPf());
        }
    }

    public function testCadaPagadorPossuiObjetoPessoaJuridica()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Pj',
            $order->getPayer()->getPj());
        }
    }
}
