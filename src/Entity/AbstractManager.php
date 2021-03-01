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

namespace Gpupo\SubmarinoSdk\Entity;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\CommonSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;
use Gpupo\CommonSdk\Entity\Metadata\MetadataContainer;

abstract class AbstractManager extends ManagerAbstract implements ManagerInterface
{
    const JSON_DATA_KEY = 'ubdefined';
    
    public function fetchRemoteDataById($itemId): ?CollectionInterface
    {
        $data = parent::findById($itemId);

        if (empty($data) || 404 === $data->get('status')) {
            return null;
        }

        return $data;
    }

    public function findById($itemId): ?CollectionInterface
    {
        $data = $this->fetchRemoteDataById($itemId);

        return empty($data) ? null : $this->factoryEntity($data->toArray());
    }

    public function fetch(int $offset = 0, int $limit = 50, array $parameters = [], string $route = 'fetch'): ?CollectionInterface
    {
        $page = $offset + 1;

        return parent::fetch($offset, $limit, array_merge([
            'page' => $page,
        ], $parameters), $route);
    }

    public function update(EntityInterface $entity, EntityInterface $existent = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function fetchPrepare($data): MetadataContainer
    {
        
        $collection = new MetadataContainer();
        $collection->getMetadata()->setTotalRows($data->count());

        $collection->add($this->factoryEntity($data->toArray()));

        return $collection;
    }

    protected function factoryEntity($data): CollectionInterface
    {
        $this->getLogger()->debug(sprintf('%s::factoryEntity',$this->getEntityName()),$data);
        $entity = $this->factoryNeighborObject($this->getEntityName(), $data);

        return $entity;
    }

}
