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

use Gpupo\SubmarinoSdk\Factory;
use Gpupo\Tests\CommonSdk\TestCaseAbstract as CommonSdkTestCaseAbstract;

abstract class TestCaseAbstract extends CommonSdkTestCaseAbstract
{
    private $factory;

    public static function getResourcesPath()
    {
        return dirname(dirname(__FILE__)).'/Resources/';
    }

    protected function getOptions()
    {
        return [
            'token'             => $this->getConstant('API_TOKEN'),
            'verbose'           => $this->getConstant('VERBOSE'),
            'registerPath'      => $this->getConstant('REGISTER_PATH'),
        ];
    }

    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = Factory::getInstance()->setup($this->getOptions(), $this->getLogger());
        }

        return $this->factory;
    }

    public function factoryClient()
    {
        return $this->getFactory()->getClient();
    }

    public function dataProviderProducts()
    {
        return $this->getResourceJson('fixture/Product/Products.json');
    }

    public function dataProviderSkus()
    {
        return $this->getResourceJson('fixture/Product/Skus.json');
    }

    public function dataProviderOrders()
    {
        return $this->getResourceJson('fixture/Order/list.json');
    }
}
