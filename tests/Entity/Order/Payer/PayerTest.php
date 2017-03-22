<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
