<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Exception\UnexpectedValueException;

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
        }
    
        return $this->set('status', $status);
    }
}
