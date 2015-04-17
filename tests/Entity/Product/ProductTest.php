<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Factory;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class ProductTest extends TestCaseAbstract
{
    protected function factory($data)
    {
        return Factory::getInstance()->createProduct($data);
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPropriedadesEObjetos(array $data)
    {
        $product = $this->factory($data);
        $this->assertEquals($data['id'], $product->getId());
        $this->assertEquals($data['name'], $product->getName());
        $this->assertEquals($data['deliveryType'], $product->getDeliveryType());
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiNbmFormatado($data)
    {
        $product = $this->factory($data);
        $nbm = $product->getNbm();
        $this->assertEquals($data['nbm']['number'], $nbm['number']);
        $this->assertEquals($data['nbm']['origin'], $nbm['origin']);
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPrecoFormatado($data)
    {
        $product = $this->factory($data);
        $price = $product->getSku()->first()->getPrice();
        foreach (['listPrice', 'sellPrice'] as $key) {
            $this->assertEquals($data['sku'][0]['price'][$key], $price[$key]);
        }
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiUmaColecaoDeSkus($data)
    {
        $product = $this->factory($data);

        foreach ($data['sku'] as $item) {
            $productSku = $product->getSku()->current();
            $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Sku\Sku', $productSku);
            $this->assertTrue($product->getSku()->hasId($item['id']));
            $this->assertTrue($product->has($productSku));
            $this->assertEquals($item['name'], $productSku->getName());
            $this->assertEquals($item['description'], $productSku->getDescription());

            $skuEan = $productSku->getEan();
            foreach ($item['ean'] as $ean) {
                $this->assertEquals($ean, current($skuEan));
                next($skuEan);
            }

            $product->getSku()->next();
        }
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiObjetoManufacturer($data)
    {
        $product = $this->factory($data);
        $productManufacturer = $product->getManufacturer();
        $this->assertInstanceOf('Gpupo\SubmarinoSdk\Entity\Product\Manufacturer', $productManufacturer);
        $this->assertEquals($data['manufacturer']['name'], $productManufacturer->getName());
        $this->assertEquals($data['manufacturer']['model'], $productManufacturer->getModel());
        $this->assertEquals($data['manufacturer']['warrantyTime'], $productManufacturer->getWarrantyTime());

        return $product;
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testEntregaJson($data)
    {
        $product = $this->factory($data);
        $json = $product->toJson();
        $array = json_decode($json, true);

        $this->assertArrayHasKey('sku', $array);
        $this->assertArrayHasKey('description', current($array['sku']));

        $this->assertArrayHasKey('manufacturer', $array);
        foreach (array('name', 'model',  'warrantyTime') as $key) {
            $this->assertArrayHasKey($key, $array['manufacturer']);
            $this->assertEquals($data['manufacturer'][$key], $array['manufacturer'][$key]);
        }

        $this->assertEquals($data['nbm']['number'], $array['nbm']['number']);
        $this->assertEquals($data['nbm']['origin'], $array['nbm']['origin']);

        foreach ($data['sku'] as $item) {
            $sku = current($array['sku']);
            $this->assertEquals($item['name'], $sku['name']);
            $this->assertEquals($item['description'], $sku['description']);

            $skuEan = $sku['ean'];
            foreach ($item['ean'] as $ean) {
                $this->assertEquals($ean, current($skuEan));
                next($skuEan);
            }

            next($array['sku']);
        }

        return $array;
    }
}
