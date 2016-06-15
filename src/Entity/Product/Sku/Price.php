<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method mixed getSellPrice()
 * @method setSellPrice(mixed $sellPrice)
 * @method mixed getListPrice()
 * @method setListPrice(mixed $listPrice)
 */
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
