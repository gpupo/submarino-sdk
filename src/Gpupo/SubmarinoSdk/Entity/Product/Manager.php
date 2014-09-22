<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\ManagerAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Manager extends ManagerAbstract implements ManagerInterface
{
    protected function exceptionHandler($response)
    {
        return new \Exception($response->getData()->getMessage(), $response->getHttpStatusCode());
    }
    
    protected function checkSuccess($response)
    {
        if ($response->getHttpStatusCode() != 200) {
            throw $this->exceptionHandler($response);
        }
        
        return true;
    }
    
    public function save(EntityInterface $entity)
    {
        return $this->checkSuccess($this->getClient()->post('/product', $entity->toJson()));
    }
    
    public function findById($id)
    {
        $response = $this->getClient()->get('/product/' . $id);
        
        if ($this->checkSuccess($response)) {
            $product = new Product($response->getData()->toArray());
        
            return $product;
        }
    }
    
    public function fetch($offset = 1, $limit = 50)
    {
        $response = $this->getClient()->get('/product?offset=' . $offset 
            . '&limit=' . $limit );
        
        if ($this->checkSuccess($response)) {
            $product = new Product($response->getData()->toArray());
        
            return $product;
        }
    }
    
}
