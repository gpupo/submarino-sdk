<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;

class Manager extends ManagerAbstract implements ManagerInterface
{
    protected $maps = [
        'save'      => ['POST', '/product'],
        'findById'  => ['GET', '/product/{itemId}'],
        'fetch'     => ['GET', '/product?offset={offset}&limit={limit}'],
    ];
}
