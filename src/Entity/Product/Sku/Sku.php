<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\SubmarinoSdk\Entity\Product\Sku;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Sku extends EntityAbstract implements EntityInterface
{
    protected function setUp()
    {
        $this->setOptionalSchema(['height', 'width', 'length']);
    }

    public function getSchema()
    {
        return [
            'id' => 'string',
            'name' => 'string',
            'description' => 'string',
            'ean' => 'array',
            'height' => 'number',
            'width' => 'number',
            'length' => 'number',
            'weight' => 'number',
            'stockQuantity' => 'integer',
            'enable' => 'boolean',
            'price' => 'object',
            'updatedAt' => 'string',
            'urlImage' => 'array',
            'crossDocking' => 'integer',
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
}
