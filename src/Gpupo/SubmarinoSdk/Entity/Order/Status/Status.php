<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Status extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'status'    => 'string',
            'shipped'   => 'object',
            'delivered' => 'object',
        ];
    }

    public function __toString()
    {
        return $this->getStatus();
    }

    public function setStatus($status)
    {
        switch ($status) {
            case 'SHIPPED':
                $this->getShipped()->setRequired();
                break;
            case 'DELIVERED':
                $this->getDelivered()->setRequired();
                break;
        }

        $this->set('status', $status);

        return $this;
    }
}
