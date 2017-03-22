<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
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
            'id'           => 'string',
            'name'         => 'string',
            'sku'          => 'object',
            'manufacturer' => 'object',
            'deliveryType' => 'string',
            'nbm'          => 'array',
        ];
    }

    public function has(Sku\Sku $sku)
    {
        return $this->getSku()->hasId($sku->getId());
    }
}
