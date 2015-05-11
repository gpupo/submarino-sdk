<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
            'invoiced'           => 'object',
            'shipped'            => 'object',
            'shipmentException'  => 'object',
            'delivered'          => 'object',
            'unavailable'        => 'object',
            'status'             => 'string',
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
