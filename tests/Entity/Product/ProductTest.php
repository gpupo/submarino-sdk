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

namespace Gpupo\Tests\SubmarinoSdk\Entity\Product;

use Gpupo\SubmarinoSdk\Entity\Product\Product;
use Gpupo\SubmarinoSdk\Factory;
use Gpupo\Tests\SubmarinoSdk\TestCaseAbstract;

class ProductTest extends TestCaseAbstract
{
    public static function setUpBeforeClass()
    {
        self::displayClassDocumentation(new Product());
    }

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
        $this->assertSame($data['id'], $product->getId());
        $this->assertSame($data['name'], $product->getName());
        $this->assertSame($data['deliveryType'], $product->getDeliveryType());
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiNbmFormatado($data)
    {
        $product = $this->factory($data);
        $nbm = $product->getNbm();
        $this->assertSame($data['nbm']['number'], $nbm['number']);
        $this->assertSame($data['nbm']['origin'], $nbm['origin']);
    }

    /**
     * @dataProvider dataProviderProducts
     */
    public function testPossuiPrecoFormatado($data)
    {
        $product = $this->factory($data);
        $price = $product->getSku()->first()->getPrice();
        foreach (['listPrice', 'sellPrice'] as $key) {
            $this->assertSame($data['sku'][0]['price'][$key], $price[$key]);
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
            $this->assertSame($item['name'], $productSku->getName());
            $this->assertSame($item['description'], $productSku->getDescription());

            $skuEan = $productSku->getEan();
            foreach ($item['ean'] as $ean) {
                $this->assertSame($ean, current($skuEan));
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
        $this->assertSame($data['manufacturer']['name'], $productManufacturer->getName());
        $this->assertSame($data['manufacturer']['model'], $productManufacturer->getModel());
        $this->assertSame($data['manufacturer']['warrantyTime'], $productManufacturer->getWarrantyTime());

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
        foreach (['name', 'model',  'warrantyTime'] as $key) {
            $this->assertArrayHasKey($key, $array['manufacturer']);
            $this->assertSame($data['manufacturer'][$key], $array['manufacturer'][$key]);
        }

        $this->assertSame($data['nbm']['number'], $array['nbm']['number']);
        $this->assertSame($data['nbm']['origin'], $array['nbm']['origin']);

        foreach ($data['sku'] as $item) {
            $sku = current($array['sku']);
            $this->assertSame($item['name'], $sku['name']);
            $this->assertSame($item['description'], $sku['description']);

            $skuEan = $sku['ean'];
            foreach ($item['ean'] as $ean) {
                $this->assertSame($ean, current($skuEan));
                next($skuEan);
            }

            next($array['sku']);
        }

        return $array;
    }
}
