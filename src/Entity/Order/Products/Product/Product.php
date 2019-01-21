<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Products\Product;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

/**
 * @method Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Link getLink()
 * @method                                                       setLink(Gpupo\SubmarinoSdk\Entity\Order\Products\Product\Link $link)
 * @method int                                                   getQuantity()
 * @method                                                       setQuantity(integer $quantity)
 * @method float                                                 getPrice()
 * @method                                                       setPrice(float $price)
 * @method float                                                 getFreight()
 * @method                                                       setFreight(float $freight)
 * @method float                                                 getDiscount()
 * @method                                                       setDiscount(float $discount)
 */
class Product extends EntityAbstract implements EntityInterface
{
    public function getSchema()
    {
        return  [
            'link' => 'object',
            'quantity' => 'integer',
            'price' => 'number',
            'freight' => 'number',
            'discount' => 'number',
        ];
    }
}
