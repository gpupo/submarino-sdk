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

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Order extends EntityAbstract implements EntityInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function getSchema(): array
    {
        return [
            'code' => 'string',
            'channel' => 'string',
            'shipping_method' => 'string',
            'shipping_cost' => 'number',
            'total_ordered' => 'number',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'customer' => 'array',
        ];
    }
}
