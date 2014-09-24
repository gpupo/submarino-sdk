<?php

namespace Gpupo\SubmarinoSdk\Entity;

use Gpupo\CommonSdk\Entity\ManagerAbstract as CommonAbstract;
use Gpupo\CommonSdk\Entity\ManagerInterface;

abstract class ManagerAbstract extends CommonAbstract implements ManagerInterface
{
    protected $entity;
    
    public function fetch($offset = 1, $limit = 50)
    {
        $data = parent::fetch($offset, $limit);
        
        if ($data->getTotal() > 0) {
            $method = 'get' . $this->getEntityName() . 's';
            
            return $this->factoryEntityCollection($data->$method());
        }
        
        return null;
    }
}
