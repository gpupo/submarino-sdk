<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\EntityAbstract;
use Gpupo\SubmarinoSdk\Entity\EntityInterface;

class Price extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'sellPrice' => 'number',
            'listPrice' => 'number',
        ];
    }
}
