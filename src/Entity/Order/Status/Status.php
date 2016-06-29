<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\Invoiced getInvoiced()
 * @method setInvoiced(Gpupo\SubmarinoSdk\Entity\Order\Status\Invoiced $invoiced)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\Shipped getShipped()
 * @method setShipped(Gpupo\SubmarinoSdk\Entity\Order\Status\Shipped $shipped)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\ShipmentException getShipmentException()
 * @method setShipmentException(Gpupo\SubmarinoSdk\Entity\Order\Status\ShipmentException $shipmentException)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\Delivered getDelivered()
 * @method setDelivered(Gpupo\SubmarinoSdk\Entity\Order\Status\Delivered $delivered)
 * @method Gpupo\SubmarinoSdk\Entity\Order\Status\Unavailable getUnavailable()
 * @method setUnavailable(Gpupo\SubmarinoSdk\Entity\Order\Status\Unavailable $unavailable)
 * @method string getStatus()
 * @method setStatus(string $status)
 */
class Status extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'invoiced'          => 'object',
            'shipped'           => 'object',
            'shipmentException' => 'object',
            'delivered'         => 'object',
            'unavailable'       => 'object',
            'status'            => 'string',
        ];
    }

    public function __toString()
    {
        return $this->getStatus();
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
