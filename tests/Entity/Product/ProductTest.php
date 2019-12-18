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

namespace Entity\Product;

use Gpupo\CommonSdk\Tests\Traits\EntityTrait;
use Gpupo\SubmarinoSdk\Entity\Product\Product;
use  Gpupo\SubmarinoSdk\Tests\TestCaseAbstract;

/**
 * @coversDefaultClass \Gpupo\SubmarinoSdk\Entity\Product\Product
 */
class ProductTest extends TestCaseAbstract
{
    use EntityTrait;

    /**
     * @return \Gpupo\SubmarinoSdk\Entity\Product\Product
     */
    public function dataProviderProduct()
    {
        $data = $this->getResourceJson('mockup/products/detail.json');

        return $this->dataProviderEntitySchema(Product::class, $data);
    }

    /**
     * @testdox ``getSchema()``
     * @cover ::getSchema
     * @group todo
     * @dataProvider dataProviderProduct
     */
    public function testGetSchema(Product $product)
    {
        $schema = $product->getSchema();
        $this->assertIsArray($schema);
    }

    /**
     * @testdox Possui método ``getName()`` para acessar Name
     * @dataProvider dataProviderProduct
     * @cover ::get
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testGetName(Product $product, $expected = null)
    {
        $this->assertSchemaGetter('name', 'string', $product, $expected);
    }

    /**
     * @testdox Possui método ``setName()`` que define Name
     * @dataProvider dataProviderProduct
     * @cover ::set
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testSetName(Product $product, $expected = null)
    {
        $this->assertSchemaSetter('name', 'string', $product);
    }

    /**
     * @testdox Possui método ``getCategories()`` para acessar Categories
     * @dataProvider dataProviderProduct
     * @cover ::get
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testGetCategories(Product $product, $expected = null)
    {
        $this->assertSchemaGetter('categories', 'array', $product, $expected);
    }

    /**
     * @testdox Possui método ``setCategories()`` que define Categories
     * @dataProvider dataProviderProduct
     * @cover ::set
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testSetCategories(Product $product, $expected = null)
    {
        $this->assertSchemaSetter('categories', 'array', $product);
    }

    /**
     * @testdox Possui método ``getPrice()`` para acessar Price
     * @dataProvider dataProviderProduct
     * @cover ::get
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testGetPrice(Product $product, $expected = null)
    {
        $this->assertSame((float) $product['price'], (float) $expected['price'], 'Compare float prices');
    }

    /**
     * @testdox Possui método ``setPrice()`` que define Price
     * @dataProvider dataProviderProduct
     * @cover ::set
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testSetPrice(Product $product, $expected = null)
    {
        $this->assertSchemaSetter('price', 'number', $product);
    }

    /**
     * @testdox Possui método ``getDescription()`` para acessar Description
     * @dataProvider dataProviderProduct
     * @cover ::get
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testGetDescription(Product $product, $expected = null)
    {
        $this->assertSchemaGetter('description', 'array', $product, $expected);
    }

    /**
     * @testdox Possui método ``setDescription()`` que define Description
     * @dataProvider dataProviderProduct
     * @cover ::set
     * @cover ::getSchema
     * @small
     *
     * @param null|mixed $expected
     */
    public function testSetDescription(Product $product, $expected = null)
    {
        $this->assertSchemaSetter('description', 'array', $product);
    }
}
