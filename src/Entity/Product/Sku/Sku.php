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
 * @method string getId()
 * @method setId(string $id)
 * @method string getName()
 * @method setName(string $name)
 * @method string getDescription()
 * @method setDescription(string $description)
 * @method array getEan()
 * @method setEan(array $ean)
 * @method float getHeight()
 * @method setHeight(float $height)
 * @method float getWidth()
 * @method setWidth(float $width)
 * @method float getLength()
 * @method setLength(float $length)
 * @method float getWeight()
 * @method setWeight(float $weight)
 * @method integer getStockQuantity()
 * @method setStockQuantity(integer $stockQuantity)
 * @method boolean getEnable()
 * @method setEnable(boolean $enable)
 * @method Gpupo\SubmarinoSdk\Entity\Product\Sku\Price getPrice()
 * @method setPrice(Gpupo\SubmarinoSdk\Entity\Product\Sku\Price $price)
 * @method string getUpdatedAt()
 * @method setUpdatedAt(string $updatedAt)
 * @method array getUrlImage()
 * @method setUrlImage(array $urlImage)
 */
class Sku extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return [
            'id'            => 'string',
            'name'          => 'string',
            'description'   => 'string',
            'ean'           => 'array',
            'height'        => 'number',
            'width'         => 'number',
            'length'        => 'number',
            'weight'        => 'number',
            'stockQuantity' => 'integer',
            'enable'        => 'boolean',
            'price'         => 'object',
            'updatedAt'     => 'string',
            'urlImage'      => 'array',
        ];
    }

    protected function toStock()
    {
        return $this->piece('stockQuantity', 'quantity');
    }

    protected function toStatus()
    {
        return $this->piece('enable', 'enable');
    }

    protected function setUp()
    {
        $this->setOptionalSchema(['height', 'width', 'length']);
    }
}
