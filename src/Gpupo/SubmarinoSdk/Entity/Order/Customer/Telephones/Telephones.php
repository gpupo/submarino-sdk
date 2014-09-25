<?php

namespace Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Telephones extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    { 
        return  [
            'main' => 'object',
            'secondary' => 'object',
            'business' => 'object',
        ];                 
    }
}
