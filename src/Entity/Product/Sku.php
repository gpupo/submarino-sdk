<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <g@g1mr.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <http://www.g1mr.com/>.
 */

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\Common\Entity\CollectionAbstract;
use Gpupo\CommonSdk\Exception\UnexpectedValueException;

class Sku extends CollectionAbstract
{
    protected $index = [];

    public function __construct(array $elements = [])
    {
        parent::__construct();

        if (array_key_exists('id', $elements)) {
            $elements = [$elements];
        }

        foreach ($elements as $data) {
            if (array_key_exists('ean', $data) && !is_array($data['ean'])) {
                $data['ean'] = [$data['ean']];
            }

            if (array_key_exists('urlImage', $data) && !is_array($data['urlImage'])) {
                $data['urlImage'] = [$data['urlImage']];
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
        return in_array($id, $this->index, true);
    }
}
