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

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Status extends EntityAbstract implements EntityInterface
{
    public function __toString()
    {
        return $this->getStatus();
    }

    public function getSchema(): array
    {
        return  [
            'invoiced' => 'object',
            'shipped' => 'object',
            'shipmentException' => 'object',
            'delivered' => 'object',
            'unavailable' => 'object',
            'status' => 'string',
        ];
    }

    public function setStatus($status)
    {
        switch ($status) {
            case 'INVOICED':
                $this->getInvoiced()->setRequired();

                break;
            case 'SHIPPED':
                $this->getShipped()->setRequired();

                break;
            case 'DELIVERED':
                $this->getDelivered()->setRequired();

                break;
            case 'UNAVAILABLE':
                $this->getUnavailable()->setRequired();

                break;
        }

        $this->set('status', $status);

        return $this;
    }
}
