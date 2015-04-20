<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\FactoryAbstract;

class Factory extends FactoryAbstract
{
    public function getNamespace()
    {
        return '\Gpupo\SubmarinoSdk\Entity\\';
    }

    public function setClient(array $clientOptions = [])
    {
        $this->client =  new Client($clientOptions, $this->logger);
    }

    protected function getSchema($namespace)
    {
        return [
            'product' => [
                'class'     => $namespace.'Product\Factory',
                'method'    => 'factoryProduct',
                'manager'   => $namespace.'Product\Manager',
            ],
            'sku' => [
                'class'     => $namespace.'Product\Factory',
                'method'    => 'factorySku',
                'manager'   => $namespace.'Product\Sku\Manager',
            ],
            'order' => [
                'class'     => $namespace.'Order\Factory',
                'method'    => 'factoryOrder',
                'manager'   => $namespace.'Order\Manager',
            ],
        ];
    }
}
