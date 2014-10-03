<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Exception\UnexpectedValueException;

class Sku extends CollectionAbstract
{
    protected $index = [];

    public function __construct(array $elements = array())
    {
        parent::__construct();
        
        if (array_key_exists('id', $elements)) {
            $elements = [$elements];
        }

        foreach ($elements as $data) {

            if (array_key_exists('ean', $data) && !is_array($data['ean'])) {
                $data['ean'] = [$data['ean']];
            }

            $this->add(new Sku\Sku($data));
        }
    }

    public function add($element)
    {
        $skuId = $element->getId();
        
        if ($this->hasId($skuId)) {
            throw new UnexpectedValueException('Sku already exists!');
        }

        $this->index[] = $skuId;
     
        return parent::add($element);
    }

    public function hasId($id)
    {
        return in_array($id, $this->index);
    }

}
