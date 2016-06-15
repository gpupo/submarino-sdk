<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Order\Products\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Link getLink()
 * @method setLink(Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Link $link)
 * @method int getQuantity()
 * @method setQuantity(integer $quantity)
 * @method float getPrice()
 * @method setPrice(float $price)
 * @method float getFreight()
 * @method setFreight(float $freight)
 * @method float getDiscount()
 * @method setDiscount(float $discount)
 */
class Product extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'link'     => 'object',
            'quantity' => 'integer',
            'price'    => 'number',
            'freight'  => 'number',
            'discount' => 'number',
        ];
    }
}
