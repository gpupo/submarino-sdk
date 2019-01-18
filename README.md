# submarino-sdk

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)

[![Paypal Donations](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK6F2WRKG7GNN&item_name=submarino-sdk)

## Requisitos para uso

* PHP >= *7.3*
* [curl extension](http://php.net/manual/en/intro.curl.php)
* [Composer Dependency Manager](http://getcomposer.org)

Este componente **não é uma aplicação Stand Alone** e seu objetivo é ser utilizado como biblioteca.
Sua implantação deve ser feita por desenvolvedores experientes.

**Isto não é um Plugin!**

As opções que funcionam no modo de comando apenas servem para depuração em modo de
desenvolvimento.

A documentação mais importante está nos testes unitários. Se você não consegue ler os testes unitários, eu recomendo que não utilize esta biblioteca.

## Direitos autorais e de licença

Este componente está sob a [licença MIT](https://github.com/gpupo/common-sdk/blob/master/LICENSE)

Para a informação dos direitos autorais e de licença você deve ler o arquivo
de [licença](https://github.com/gpupo/common-sdk/blob/master/LICENSE) que é distribuído com este código-fonte.

### Resumo da licença

Exigido:

- Aviso de licença e direitos autorais

Permitido:

- Uso comercial
- Modificação
- Distribuição
- Sublicenciamento
- Proibido

Proibido:

- Responsabilidade Assegurada

---

## Instalação

Adicione o pacote [submarino-sdk](https://packagist.org/packages/gpupo/submarino-sdk) ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/submarino-sdk:^4.3

Este exemplo demonstra o uso simplificado a partir do ``Factory``:

```php
<?php

use Gpupo\SubmarinoSdk\Factory;

$submarinoSdk = Factory::getInstance()->setup([
    'token'     => '7Ao82svbm#6',
    'version'   => 'sandbox',
]);

```

## Exemplos de uso

### Acesso a lista de produtos cadastrados

```php
<?php
//...
$manager = $submarinoSdk->factoryManager('product'));
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

```

### Acesso a informações de um produto específico

```php
<?php
//...
$produto = $manager->findById(9));
echo $product->getName();

```

### Criação de um produto

Veja o formato de ``$data`` em Resources/fixture/Products.json

```php
<?php
//...
$data = [];
$product = $submarinoSdk->createProduct($data);

foreach ($data['sku'] as $item) {
    $sku = $submarinoSdk->createSku($item);
    $product->getSku()->add($sku);
}

$manager->save($product);

```

### Exemplos de manutenção de Produtos

```php
<?php
//...
$manager = $submarinoSdk->factoryManager('product'));

// Acesso a lista de produtos cadastrados:
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

// Acesso a informações de um produto cadastrado e com identificador conhecido:
$produto = $manager->findById(9)); // Objeto Produto
echo $product->getName(); // Acesso ao nome do produto #9


// Criação de um produto:
$data = []; // Veja o formato de $data em Resources/fixture/Products.json
$product = $submarinoSdk->createProduct($data);

foreach ($data['sku'] as $item) {
    $sku = $submarinoSdk->createSku($item);
    $product->getSku()->add($sku);
}

$manager->save($product);

//Adicionando SKU ao produto:
$skuData = []; // Defina o valor deste array conforme o esquema disponível em Resources/
$novoSku = $submarinoSdk->createSku($skuData);
$product->getSku()->add($novoSku);
$manager->save($product);

```

### Exemplos de manutenção de Pedidos

```php
<?php
//...
$manager = $submarinoSdk->factoryManager('order'));

$orderList = $manager->fetch(); //Recebe uma coleção ``\Gpupo\SubmarinoSdk\Entity\Order\Order``

foreach ($orderList as $order) {
	//Atualizando dados de ENVIO do pedido:
   	$order->getStatus()->setStatus('DELIVERED')
   		->getDelivered()->setDeliveredCustomerDate(date('Y-m-d H:i:s'))
   		->setTrackingProtocol('RE983737722BR')
        ->setEstimatedDelivery('2015-12-25 01:00:00');
	$manager->saveStatus($order);
}

//Acessando informações de um pedido específico

$order = $manager->findById(339938882);
echo $order->getId(); //339938882
echo $order->getSiteId(); // 03-589-01
echo $order->getStore(); // SUBMARINO
echo $order->getStatus(); // PROCESSING

//Movendo pedido de situação na B2W:
$order->getStatus()->setStatus('PROCESSING');
$manager->saveStatus($order);

```

#### Uso de cache para otimização de updates

```php
<?php
//...
use Gpupo\Cache\CacheItem;
use Gpupo\Cache\CacheItemPool;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;

$data = []; //Your SKU array!

$sku = Factory::createSku($data);

$pool = new CacheItemPool('Memcached');
$key = 'sku-foo';
$item = new CacheItem($key);
$item->set($sku, 60);
$pool->save($item);

// mude o mundo... e pense que está em uma nova execução, alguns minutos depois ...
$sku = Factory::createSku($data);
$previousSku = $pool->getItem($key)->get();
$sku->setPrevious($previousSku);
$sku->save();

```

#### Uso de confirmação ou rejeição de um pedido

```php
<?php
//...
/**
 * https://api-sandbox.bonmarketplace.com.br/docs/confirmacaoPedido.shtml
 *
 * @var \Gpupo\SubmarinoSdk\Entity\Order\Manager $sdkOrderManager
 * @var \Gpupo\SubmarinoSdk\Entity\Order\Order   $order
 * @var \My\Awesome\Order\Creator                $yourOrderCreator
 */
foreach ($sdkOrderManager->fetch() as $order) {
    // Sua implementação de criação de pedido
    $successfully = $yourOrderCreator->createOrder($order);
    // POST para: http://api-marketplace.bonmarketplace.com.br/order/{ORDER_ID}/confirm
    $sdkOrderManager->confirm(
        $order->getId(),
        $successfully,
        $successfully ? 'Success' : 'Failure'
    );
}

```
----

<!-- console -->

<!-- console -->

---

## Console

Lista de comandos disponíveis:

    ./bin/main

Você pode verificar suas credenciais Cnova na linha de comando:

    ./bin/main credential

<!-- links -->

<!-- links -->

---

## Links


* [Documentação oficial](https://api-sandbox.bonmarketplace.com.br/docs/)
* [Submarino-sdk Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) no packagist.org
* [Marketplace-bundle Composer Package](https://opensource.gpupo.com/MarkethubBundle/) - Integração deste pacote com Symfony
* [Outras SDKs para o Ecommerce do Brasil](https://opensource.gpupo.com/common-sdk/)

<!-- links-common -->


<!-- dev -->

<!-- dev -->

---

## Desenvolvimento

	git clone --depth=1  git@github.com:gpupo/submarino-sdk.git

	cd submarino-sdk;

    ant;

Personalize a configuração do ``phpunit``:

    cp phpunit.xml.dist phpunit.xml;


Personalize os parâmetros!



*Dica*: Verifique os logs gerados em ``var/log/main.log``
