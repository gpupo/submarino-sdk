<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $maps = [
        'save'      => ['POST', '/product'],
        'findById'  => ['GET', '/product/{itemId}'],
        'fetch'     => ['GET', '/product?offset={offset}&limit={limit}'],
    ];
}
