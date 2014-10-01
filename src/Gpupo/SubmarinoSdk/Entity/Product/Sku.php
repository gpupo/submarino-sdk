<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\CollectionAbstract;

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

        $this->index[] = $element->getId();
     
        return parent::add($element);
    }

    public function hasId($id)
    {
        return in_array($id, $this->index);
    }

}
