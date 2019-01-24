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

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Entity\AbstractManager;

class Manager extends AbstractManager
{
    protected $entity = 'Product';

    protected $maps = [
        'save' => ['POST', '/product'],
        'findById' => ['GET', '/product/{itemId}'],
        'update' => ['PUT', '/product/{itemId}'],
        'fetch' => ['GET', '/product?page={page}&per_page={limit}'],
    ];

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
    }
}
