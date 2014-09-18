<?php

namespace Gpupo\SubmarinoSdk\Entity;

abstract class EntityAbstract extends CollectionAbstract
{   
    public function __construct(array $data = null)
    {
        if (!$this instanceof EntityInterface) {
            throw new \Exception('EntityInterface deve ser implementada');
        }
        
        $schema = $this->getSchema();
        
        if (!empty($schema)) {
            parent::__construct($this->initSchema($this->getSchema(), $data));
        }
    }
    
    protected function initSchema(array $schema, $data)
    {
        foreach($schema as $key => $value) {
            if (!empty($data) && array_key_exists($key, $data)) {
                $schema[$key] = $data[$key];
            } elseif ($value == 'collection') {
                $schema[$key] = $this->factoryCollection();
            } elseif ($value == 'array') {
                $schema[$key] = [];
            }
        } 
        
        return $schema;
    }
    
    protected function factoryCollection()
    {
        return new Collection;
    }
    
    public function toArray()
    {
        if ($this->validate()) {
            return parent::toArray();
        }
    }

    protected function validate()
    {
        foreach($this->getSchema() as $key => $value) {
            $current = $this->get($key);
            if ($value == 'integer') {
                if (intval($current) !== $current) {
                    throw new \InvalidArgumentException($key . 'should have value of type Integer valid');
                }
            } elseif ($value == 'number') {
                if (floatval($current) != $current) {
                    throw new \InvalidArgumentException($key . 'should have value of type Number valid');
                }
            }
        }
        
        return true;
    }
}
