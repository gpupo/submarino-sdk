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

namespace Gpupo\SubmarinoSdk\Tests;

use Gpupo\CommonSdk\Tests\TestCaseAbstract as Core;
use Gpupo\SubmarinoSdk\Factory;

abstract class TestCaseAbstract extends Core
{
    private $factory;

    public static function getResourcesPath()
    {
        return \dirname(__DIR__).'/Resources/';
    }

    public function factoryClient()
    {
        return $this->getFactory()->getClient();
    }

    public function dataProviderProducts()
    {
        return $this->getResourceJson('fixture/Product/Products.json');
    }

    public function dataProviderOrders()
    {
        return $this->getResourceJson('fixture/Order/list.json');
    }

    protected function getOptions()
    {
        return getenv();
    }

    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = Factory::getInstance()->setup($this->getOptions(), $this->getLogger());
        }

        return $this->factory;
    }
}
