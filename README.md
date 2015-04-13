[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/?branch=master)

SDK Não Oficial para integração a partir de aplicações PHP com as APIs do Submarino Marketplace

## Documentação


### Uso de Factoy (novo)

Este exemplo demonstra o uso simplificado a partir de um único objeto Factory:


```PHP
<?php
///...
use Gpupo\SubmarinoSdk\Factory;

$factory = Factory::getInstance()->setup(['token' => '7Ao82svbm#6', 'version' => 'sandbox']);

$manager = $factory->factoryManager('product'));

// Acesso a lista de produtos cadastrados:
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

// Acesso a informações de um produto cadastrado e com identificador conhecido:
$produto = $manager->findById(9)); // Objeto Produto
echo $product->getName(); // Acesso ao nome do produto #9


// Criação de um produto:
$data = []; // Veja o formato de $data em Resources/fixture/Products.json
$product = $factory->createProduct($data);

foreach ($data['sku'] as $item) {
    $sku = $factory->createSku($item);
    $product->getSku()->add($sku);
}

$manager->save($product);

```


### Exemplos de manutenção de Produtos

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

#### Uso de cache para otimização de updates

```php

use Gpupo\Cache\CacheItem;
use Gpupo\Cache\CacheItemPool;
use Gpupo\SubmarinoSdk\Entity\Product\Factory;

$data = []; //Your SKU array!

$sku = Factory::factorySku($data);

$pool = new CacheItemPool('Memcached');
$key = 'sku-foo';
$item = new CacheItem($key);
$item->set($sku, 60);
$pool->save($item);

// mude o mundo... e pense que está em uma nova execução, alguns minutos depois ...

$sku = Factory::factorySku($data);
$previousSku = $pool->getItem($key)->get();
$sku->setPrevious($previousSku);
$sku->save();

```


### Exemplos de manutenção de Pedidos

```PHP
<?php
//...
use Gpupo\SubmarinoSdk\Entity\Order\Manager;
//...
$manager = new Manager($client);
$orderList = $manager->fetch(); //Recebe uma coleção de itens \Gpupo\SubmarinoSdk\Entity\Order\Order

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


---- 


* [Documentação oficial](https://api-sandbox.bonmarketplace.com.br/docs/)

## Licença

MIT, veja LICENSE.


## Instalação

Adicione o pacote ``submarino-sdk`` ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/submarino-sdk

---

# Desenvolvimento

    git clone --depth=1  git@github.com:gpupo/submarino-sdk.git

    cd submarino-sdk;
    
    composer install;

    phpunit;


Personalize a configuração do ``phpunit``:

    cp phpunit.xml.dist phpunit.xml;

Insira sua Token de Sandbox em ``phpunit.xml``:

```XML
    <!-- Customize your parameters ! -->
    <php>
        <const name="API_TOKEN" value=""/>
        <const name="VERBOSE" value="false"/>
    </php>
```

Rode os testes localmente:

    $ phpunit
  

## Links

* [Submarino-sdk Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) no packagist.org

---

## Propriedades (Testdox)

<!--
phpunit --tesdox | grep -vi php |  sed "s/.*\[/-&/" | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' 
-->

Gpupo\Tests\SubmarinoSdk\Client

- [x] Acesso a lista de pedidos
- [x] Acesso a lista de produtos
- [x] Acesso a lista de skus
- [x] Retorna informacoes do sku informado
- [x] Atualiza estoque do sku informado
- [x] Atualiza preco do sku informado

Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer\Customer

- [x] Cada cliente possui endereco de entrega como objeto
- [x] Cada cliente possui colecao de telefones
- [x] Cada cliente possui objeto pessoa fisica
- [x] Cada cliente possui objeto pessoa juridica

Gpupo\Tests\SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones

- [x] Cada cliente possui colecao de telefones

Gpupo\Tests\SubmarinoSdk\Entity\Order\Manager

- [x] Obtem lista pedidos
- [x] Recupera informacoes de um pedido especifico
- [x] Atualiza status de um pedido
- [x] Atualiza dados de envio de um pedido
- [x] Atualiza dados de entrega de um pedido

Gpupo\Tests\SubmarinoSdk\Entity\Order\Order

- [x] Cada item de uma lista é um objeto
- [x] Cada pedido possui objeto cliente
- [x] Cada pedido possui colecao de produtos
- [x] Cada pedido possui objeto status

Gpupo\Tests\SubmarinoSdk\Entity\Order\Products\Products

- [x] Cada produto é um objeto

Gpupo\Tests\SubmarinoSdk\Entity\Order\Status\Status

- [x] Cada status possui objeto shipped
- [x] Cada status possui objeto delivered
- [x] Falha ao marcar como remetido sem possuir objeto shipped valido
- [x] Sucesso ao marcar como remetido informando objeto shipped valido
- [x] Falha ao marcar como entregue sem possuir objeto delivered valido
- [x] Sucesso ao marcar como entregue informando objeto delivered valido

Gpupo\Tests\SubmarinoSdk\Entity\Product\Manager

- [x] Obtem lista de produtos cadastrados
- [x] Recupera informacoes de um pedido especifico
- [x] Gerencia update

Gpupo\Tests\SubmarinoSdk\Entity\Product\Product

- [x] Possui propriedades é objetos
- [x] Possui nbm formatado
- [x] Possui preco formatado
- [x] Possui uma colecao de skus
- [x] Possui objeto manufacturer
- [x] Entrega json

Gpupo\Tests\SubmarinoSdk\Entity\Product\Sku\Manager

- [x] Acesso a lista de skus cadastrados
- [x] Acessa a informacoes de um sku
- [x] Gerencia atualizacoes

