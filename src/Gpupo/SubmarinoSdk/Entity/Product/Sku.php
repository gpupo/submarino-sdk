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

        foreach ($elements as $product) {
            $this->add(new Sku\Sku($product));
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
