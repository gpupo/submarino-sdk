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
use Gpupo\CommonSdk\Entity\ManagerAbstract as CommonAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;

abstract class ManagerAbstract extends CommonAbstract implements ManagerInterface
{
    public function update(EntityInterface $entity, EntityInterface $existent)
    {
        $text = 'Chamada a Atualização de entity '.$this->entity;

        return $this->log('debug', $text, [
            'entity' => $entity,
            'existent' => $existent,
        ]);
    }

    public function fetch($offset = 0, $limit = 50, array $parameters = [], $route = 'fetch'): ?CollectionInterface
    {
        $data = parent::fetch($offset, $limit, $parameters);

        if ($data->getTotal() > 0) {
            $method = 'get'.$this->getEntityName().'s';

            return $this->factoryEntityCollection($data->{$method}());
        }

        return $this->factoryEntityCollection([]);
    }

    public function findById($itemId)
    {
        $data = parent::findById($itemId);

        if ($data) {
            return $this->factoryEntity($data->toArray());
        }

        return false;
    }
}
