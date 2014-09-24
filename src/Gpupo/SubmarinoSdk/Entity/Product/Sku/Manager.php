<?php

namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $entity = 'Sku';
    
    protected $maps = [
        'findById'      => ['GET', '/sku/{itemId}'],
        'fetch'         => ['GET', '/sku?offset={offset}&limit={limit}'],
        'savePrice'     => ['PUT', '/sku/{itemId}/price'],
        'saveStock'     => ['PUT', '/sku/{itemId}/stock'],
        'saveStatus'    => ['PUT', '/sku/{itemId}/status'],
    ];        
}
