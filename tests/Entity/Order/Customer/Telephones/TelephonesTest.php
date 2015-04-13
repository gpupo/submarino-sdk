<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer\Telephones;

use Gpupo\Tests\SubmarinoSdk\Entity\Order\OrderTestCaseAbstract;

class TelephonesTest extends OrderTestCaseAbstract
{
    public function testCadaClientePossuiColecaoDeTelefones()
    {
        foreach ($this->getList() as $order) {
            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones',
            $order->getCustomer()->getTelephones());

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Main',
            $order->getCustomer()->getTelephones()->getMain());

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Secondary',
            $order->getCustomer()->getTelephones()->getSecondary());

            $this->assertInstanceOf('\Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones\Business',
            $order->getCustomer()->getTelephones()->getBusiness());
        }
    }
}
