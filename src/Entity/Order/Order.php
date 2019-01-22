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
    public function getSchema()
    {
        return  [
            'id' => 'string',
            'siteId' => 'string',
            'store' => 'string',
            'purchaseDate' => 'string',
            'lastUpdate' => 'string',
            'purchaseTimestamp' => 'string',
            'lastUpdateTimestamp' => 'string',
            'status' => 'object',
            'invoiced' => 'object',
            'estimatedDeliveryDate' => 'string',
            'customer' => 'object',
            'payer' => 'object',
            'totalAmount' => 'number',
            'totalFreight' => 'number',
            'totalDiscount' => 'number',
            'totalInterest' => 'number',
            'products' => 'object',
            'shipping' => 'object',
            'paymentMethods' => 'object',
        ];
    }

    public function toStatus()
    {
        return $this->piece('status');
    }

    public function getTotalReal()
    {
        return bcsub((string) $this->getTotalAmount(), (string) $this->getTotalInterest(), 2);
    }
}
