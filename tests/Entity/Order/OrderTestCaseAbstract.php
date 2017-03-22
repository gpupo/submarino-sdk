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

namespace Gpupo\Tests\SubmarinoSdk\Entity\Order;

use Gpupo\SubmarinoSdk\Entity\Order\Manager;
use Gpupo\SubmarinoSdk\Entity\Order\Order;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

abstract class OrderTestCaseAbstract extends TestCaseAbstract
{
    protected function factoryManager()
    {
        return new Manager($this->factoryClient());
    }

    public function getList()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/list.json');

        return $this->factoryManager()->setDryRun($response)->fetch();
    }

    public function dataProviderOrderCollection()
    {
        $data = [];
        foreach ($this->getList() as $order) {
            $order->setLogger($this->getLogger());
            $data[] = [$order];
        }

        return $data;
    }

    protected function factoryInterestOrder()
    {
        $response = $this->factoryResponseFromFixture('fixture/Order/interest.json');

        return $this->factoryManager()->setDryRun($response)->findById(381264028);
    }
}
