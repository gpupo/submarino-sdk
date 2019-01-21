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

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer\Telephones;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

/**
 * @coversNothing
 */
class TelephonesTest extends OrderTestCaseAbstract
{
    public function testCadaClientePossuiColecaoDeTelefones()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getCustomer()->getTelephones()
            );

            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Main',
            $order->getCustomer()->getTelephones()->getMain()
            );

            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Secondary',
            $order->getCustomer()->getTelephones()->getSecondary()
            );

            $this->assertInstanceOf(
                '\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Business',
            $order->getCustomer()->getTelephones()->getBusiness()
            );
        }
    }
}
