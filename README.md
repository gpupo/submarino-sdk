SDK Não Oficial para integração a partir de aplicações PHP com as APIs do Submarino Marketplace

[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)

## Documentação

Exemplos de manutenção de produtos:

```PHP
<?php
///...
use Gpupo\SubmarinoSdk\Client;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;

$client = new Client(['token' => '7Ao82svbm#6', 'version' => 'sandbox']);

$manager = new Manager($client);

// Acesso a lista de produtos cadastrados:
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

// Acesso a informações de um produto cadastrado e com identificador conhecido:
$produto = $manager->findById(9)); // Objeto Produto
echo $product->getName(); // Acesso ao nome do produto #9


// Criação de um produto:
$data = []; // Veja o formato de $data em Resources/fixture/Products.json
$product = Factory::factoryProduct($data);

foreach ($data['sku'] as $item) {
    $sku = Factory::factorySku($item);
    $product->getSku()->add($sku);
}

$manager->save($product);

//Adicionando SKU ao produto:
$skuData = []; // Defina o valor deste array conforme o esquema disponível em Resources/
$novoSku = Factory::factorySku($skuData);
$product->getSku()->add($novoSku);
$manager->save($product);


```
* [Documentação oficial](https://api-marketplace.submarino.com.br/docs/)

## License

MIT, see LICENSE.


## Install

The recommended way to install is [through composer](http://getcomposer.org).

    composer require gpupo/submarino-sdk:dev-master

---

# Dev

Install [through composer](http://getcomposer.org):

	composer install --dev;

Copy ``phpunit`` configuration file:

    cp phpunit.xml.dist phpunit.xml;

Customize parameters in ``phpunit.xml``:

```XML
    <!-- Customize your parameters ! -->
    <php>
        <const name="API_TOKEN" value=""/>
        <const name="VERBOSE" value="false"/>
    </php>
```

To run localy the test suite:

    $ phpunit
  

## Links

* [Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) on packagist.org
