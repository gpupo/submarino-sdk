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
use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\SubmarinoSdk\Entity\AbstractManager;
use Gpupo\CommonSchema\ArrayCollection\Trading\Product\Product;
use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;

class Manager extends AbstractManager
{
    protected $entity = 'Product';

    protected $maps = [
        'save' => ['POST', '/product'],
        'findById' => ['GET', '/product/{itemId}'],
        'update' => ['PUT', '/product/{itemId}'],
        'fetch' => ['GET', '/product?page={page}&per_page={limit}'],
    ];

    protected function factoryEntity(array $data = []): CollectionInterface
    {
        return new Product($data);
    }

    protected function fetchPrepare($data)
    {
        $collection = new MetadataContainer();
        $collection->getMetadata()
            ->setTotalRows($data['total']);
        //
        // if (!$data->getProducts()) {
        //     $collection->clear();
        //
        //     return $collection;
        // }

        foreach ($data->getProducts() as $array) {
            // $translated = $this->translateMovementDataToCommon($array);
            // $ac = new AC($translated);
            // $movement = $this->factoryORM($ac, 'Entity\Banking\Movement\Movement');


            $product = new Product($array);
            $product = $this->factoryORM($product, 'Trading\Product\Product');

            dump($product);

            $collection->add($product);
        }

        return $collection;
    }

    public function fetch(int $offset = 0, int $limit = 50, array $parameters = [], string $route = 'fetch'): ?CollectionInterface
    {
        $page = $offset + 1;
        return parent::fetch($offset, $limit, array_merge([
            'page' => $page,
        ], $parameters));
    }

    public function update(EntityInterface $entity, EntityInterface $existent)
    {
    }
}
