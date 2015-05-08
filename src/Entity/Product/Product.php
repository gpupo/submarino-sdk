<?php

/*
 * This file is part of gpupo/submarino-sdk
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
 * @method setId(string $id)
 * @method string getName()
 * @method setName(string $name)
 * @method Gpupo\SubmarinoSdk\Entity\Product\Sku getSku()
 * @method setSku(Gpupo\SubmarinoSdk\Entity\Product\Sku $sku)
 * @method Gpupo\SubmarinoSdk\Entity\Product\Manufacturer getManufacturer()
 * @method setManufacturer(Gpupo\SubmarinoSdk\Entity\Product\Manufacturer $manufacturer)
 * @method string getDeliveryType()
 * @method setDeliveryType(string $deliveryType)
 * @method array getNbm()
 * @method setNbm(array $nbm)
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
