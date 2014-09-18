<?php

namespace Gpupo\SubmarinoSdk\Entity;

use Doctrine\Common\Collections\ArrayCollection;

abstract class CollectionAbstract extends ArrayCollection
{
    public function toArray()
    {
        $list = parent::toArray();
        
        foreach($list as $key => $value) {
            if ($value instanceof CollectionAbstract) {
                $list[$key] = $value->toArray();
            }
        }
        
        return $list;
    }
    
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Magic method that implements
     *
     * @param string $method
     * @param array $args
     *
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, $args)
    {
        $command = substr($method, 0, 3);
        $field = substr($method, 3);
        $field[0] = strtolower($field[0]);

        if ($command == "set") {
            $this->set($field, current($args));
            
            return $this;
        } else if ($command == "get") {
            return $this->get($field);
        } else if ($command == "add") {
            $this->add($field, current($args));
            
            return $this;
        } else {
            throw new \BadMethodCallException("There is no method ".$method);
        }
    }
}
