<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity;

use Gpupo\CommonSdk\Entity\ManagerAbstract as CommonAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;

abstract class ManagerAbstract extends CommonAbstract implements ManagerInterface
{
    protected $entity;

    public function fetch($offset = 0, $limit = 50, array $parameters = [])
    {
        $data = parent::fetch($offset, $limit, $parameters);

        if ($data->getTotal() > 0) {
            $method = 'get'.$this->getEntityName().'s';

            return $this->factoryEntityCollection($data->$method());
        }

        return;
    }

    /**
     * {@inheritDoc}
     *
     * @todo Tratar erros de execução que representem falha de rede e remoto
     * mas que poderiam ser contornados com mais de uma tentativa de execução
     */
    protected function retry(\Exception $exception, $i)
    {
        if ($i <= 3 && $exception->getCode() >= 500) {
            // faça alguma coisa ...
        }

        return false;
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
