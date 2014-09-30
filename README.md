SDK Não Oficial para integração a partir de aplicações PHP com as APIs do Submarino Marketplace

## Documentação

Exemplo de uso, com gravação de produto:

```PHP
<?php
///...
use Gpupo\SubmarinoSdk\Client;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;
use Gpupo\SubmarinoSdk\Entity\Product\Manager;

$client = new Client(['token' => '7Ao82svbm#6', 'version' => 'sandbox']);

$manager = new Manager($client);

$data = []; // Veja o formato de $data em Resources/fixture/Products.json
$product = Factory::factoryProduct($data);

foreach ($data['sku'] as $item) {
    $sku = Factory::factorySku($item);
    $product->getSku()->add($sku);
}

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
