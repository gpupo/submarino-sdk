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

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSchema\ORM\Entity\Catalog\Product\Product;
use Gpupo\CommonSchema\ORM\Entity\Trading\Order\Order;
use Gpupo\CommonSdk\FactoryAbstract;

class Factory extends FactoryAbstract
{
    public function getDefaultOptions()
    {
        return [
            'user_email' => 'please@fill.me',
            'api_key' => 'please@fill.me',
            'accountmanager_key' => 'please@fill.me',
            'common_schema_namespace' => '\\Gpupo\\CommonSchema\\ORM',
            'app_url' => 'http://localhost',
            'verbose' => true,
            'cacheTTL' => 3600,
            'offset' => 5,
            'limit' => 20,
        ];
    }

    public function getNamespace()
    {
        return __NAMESPACE__.'\Entity\\';
    }

    public function setClient(array $clientOptions = [])
    {
        $this->client = new Client($clientOptions, $this->getLogger());
    }

    public function getSchema(): array
    {
        return [
            'generic' => [
                'manager' => Entity\GenericManager::class,
            ],
            'product' => [
                'class' => Product::class,
                'manager' => Entity\Product\Manager::class,
            ],
            'order' => [
                'class' => Order::class,
                'manager' => Entity\Order\Manager::class,
            ],
        ];
    }
}
