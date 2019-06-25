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

namespace Gpupo\SubmarinoSdk\Translator;

use Gpupo\CommonSchema\AbstractTranslator;
use Gpupo\SubmarinoSdk\Entity\Product\Product;

class AdTranslator extends AbstractTranslator
{
    public function import()
    {
        $common = $this->getForeign();

        $product = new Product();
        $product->set('sku', $common->get('seller_product_id'));
        $product->set('name', $common->get('name'));
        $product->set('description', $common->get('description'));
        $product->set('status', 'enabled');
        // $product->set('removed', 'xxxxxx');
        $product->set('qty', $common->get('quantity'));
        $product->set('price', $common->get('price'));
        $product->set('promotional_price', $common->get('promotional_price'));
        $product->set('cost', $common->get('cost'));
        $product->set('weight', $common->get('weight'));
        $product->set('height', $common->get('height'));
        $product->set('width', $common->get('width'));
        $product->set('length', $common->get('length'));
        $product->set('brand', $common->get('brand'));
        $product->set('ean', $common->get('gtin'));
        //$product->set('nbm', 'xxxxxxx');
        $product->set('images', $common->get('images'));
        $product->set('specifications', [
            [
                'key' => 'mpn',
                'value' => $common->get('mpn'),
            ],
        ]);
        $product->set('categories', $common->get('categories'));

        return $product;
    }

    public function export()
    {
    }
}
