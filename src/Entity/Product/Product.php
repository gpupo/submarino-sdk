<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method string getId()
 * @method string getName()
 * @method Gpupo\SubmarinoSdk\Entity\Product\Sku getSku()
 * @method Gpupo\SubmarinoSdk\Entity\Product\Manufacturer getManufacturer()
 * @method string getDeliveryType()
 * @method array getNbm()
 */
class Product extends EntityAbstract implements EntityInterface
{
    protected $entity = 'Product';

    public function getSchema()
    {
        return  [
            'id'            => 'string',
            'name'          => 'string',
            'sku'           => 'object',
            'manufacturer'  => 'object',
            'deliveryType'  => 'string',
            'nbm'           => 'array',
        ];
    }

    public function has(Sku\Sku $sku)
    {
        return $this->getSku()->hasId($sku->getId());
    }
}
