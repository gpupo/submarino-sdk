<?php

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $entity = 'Order';
    
    protected $maps = [
        'save'      => ['POST', '/order'],
        'findById'  => ['GET', '/order/{itemId}'],
        'fetch'     => ['GET', '/order?offset={offset}&limit={limit}'],
    ];
}
