<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Manager extends ManagerAbstract implements ManagerInterface
{
    public function save(EntityInterface $entity)
    {
        return $this->execute('POST', '/product', $entity->toJson());
    }

    public function findById($id)
    {
        $response =  $this->perform('GET', '/product/' . $id);
        $product = new Product($response->getData()->toArray());

        return $product;
    }

    public function fetch($offset = 1, $limit = 50)
    {
        $response =  $this->perform('GET','/product?offset=' . $offset
            . '&limit=' . $limit);

        $product = new Product($response->getData()->toArray());

        return $product;
    }

}
