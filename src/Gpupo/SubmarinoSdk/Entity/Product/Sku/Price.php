<?php

namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

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
