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

use Gpupo\CommonSdk\Tests\FactoryTestAbstract;
use Gpupo\SubmarinoSdk\Factory;

/**
 * @coversNothing
 */
class FactoryTest extends FactoryTestAbstract
{
    public function getFactory()
    {
        return Factory::getInstance();
    }

    /**
     * @dataProvider dataProviderManager
     *
     * @param mixed $objectExpected
     * @param mixed $target
     */
    public function testCentralizaAcessoAManagers($objectExpected, $target)
    {
        return $this->assertInstanceOf(
            $objectExpected,
            $this->createObject($this->getFactory(), 'factoryManager', $target)
        );
    }

    public function dataProviderObjetos()
    {
        return [
            ['\Gpupo\CommonSchema\ArrayCollection\Trading\Product\Product', 'product', null],
            ['\Gpupo\CommonSchema\ArrayCollection\Trading\Order\Order', 'order', null],
        ];
    }

    public function dataProviderManager()
    {
        return [
            ['\Gpupo\SubmarinoSdk\Entity\Product\Manager', 'product'],
            ['\Gpupo\SubmarinoSdk\Entity\Order\Manager', 'order'],
        ];
    }
}
