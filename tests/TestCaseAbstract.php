<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk;

use Gpupo\SubmarinoSdk\Client;
use Gpupo\Tests\CommonSdk\TestCaseAbstract as CommonSdkTestCaseAbstract;

abstract class TestCaseAbstract extends CommonSdkTestCaseAbstract
{
    public function factoryClient()
    {
        $client = Client::getInstance()
            ->setOptions([
                'token'     => $this->getConstant('API_TOKEN'),
                'verbose'   => $this->getConstant('VERBOSE'),
            ]);

        $client->setLogger($this->getLogger());

        return $client;
    }

    public function dataProviderProducts()
    {
        return $this->getResourceJson('fixture/Products.json');
    }

    public function dataProviderSkus()
    {
        return $this->getResourceJson('fixture/Skus.json');
    }

    public function dataProviderOrders()
    {
        return $this->getResourceJson('fixture/Orders.json');
    }
}
