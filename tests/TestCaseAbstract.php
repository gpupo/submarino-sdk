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
            'token'        => $this->getConstant('API_TOKEN'),
            'verbose'      => $this->getConstant('VERBOSE'),
            'registerPath' => $this->getConstant('REGISTER_PATH'),
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
