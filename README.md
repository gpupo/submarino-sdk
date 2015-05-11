[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49/small.png)](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49)
[![Code Climate](https://codeclimate.com/repos/554bba72e30ba07c8a0050f3/badges/2930bbf5a5295c3e415c/gpa.svg)](https://codeclimate.com/repos/554bba72e30ba07c8a0050f3/feed)
[![Test Coverage](https://codeclimate.com/repos/554bba72e30ba07c8a0050f3/badges/2930bbf5a5295c3e415c/coverage.svg)](https://codeclimate.com/repos/554bba72e30ba07c8a0050f3/coverage)
[![Codacy Badge](https://www.codacy.com/project/badge/42d610984533411e9ee267c00106c38d)](https://www.codacy.com/app/g/submarino-sdk)

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

## Documentação

---

### Comandos

Lista de comandos disponíveis:

    ./bin/main

Você pode verificar suas credenciais Cnova na linha de comando:

    ./bin/main credential

---

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

## Contributors

- [@gpupo](https://github.com/gpupo)
- [@danielcosta](https://github.com/danielcosta)
- [All Contributors](https://github.com/gpupo/submarino-sdk/contributors)

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
* [marketplace-bundle Composer Package](https://packagist.org/packages/gpupo/marketplace-bundle) - Integração deste pacote com Symfony2
* [Outras SDKs para o Ecommerce do Brasil](https://github.com/gpupo/common-sdk)

---

# Propriedades dos objetos (Testdox)

<!--
Comando para geração da lista:

phpunit --testdox | grep -vi php |  sed "s/.*\[/-&/" | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/Gpupo\\Tests\\SubmarinoSdk\\/### /g' > Resources/logs/testdox.txt
-->
A lista abaixo é gerada a partir da saída da execução dos testes:

### Client


- [x] Gerencia uri de recurso
- [x] Acesso a lista de pedidos
- [x] Acesso a lista de produtos
- [x] Acesso a lista de skus
- [x] Retorna informacoes do sku informado
- [x] Atualiza estoque do sku informado
- [x] Atualiza preco do sku informado

### Entity\Order\Customer\Customer


- [x] Cada cliente possui endereco de entrega como objeto
- [x] Cada cliente possui colecao de telefones
- [x] Cada cliente possui objeto pessoa fisica
- [x] Cada cliente possui objeto pessoa juridica

### Entity\Order\Customer\Telephones\Telephones


- [x] Cada cliente possui colecao de telefones

### Entity\Order\Manager


- [x] Obtem lista pedidos
- [x] Recupera informacoes de um pedido especifico
- [x] Atualiza status de um pedido
- [x] Atualiza dados de envio de um pedido
- [x] Atualiza dados de entrega de um pedido

### Entity\Order\Order


- [x] Cada item de uma lista e um objeto
- [x] Cada pedido possui objeto cliente
- [x] Cada pedido possui objeto com dados de cobrança
- [x] Cada pedido possui colecao de produtos
- [x] Cada pedido possui objeto status
- [x] Possui loja de origem
- [x] Possui valor total do pedido
- [x] Possui valor total do frete
- [x] Possui valor total de desconto
- [x] Possui valor total de juros
- [x] Possui valor total do pedido descontado juros
- [x] O total real contém produtos somado a frete menos o desconto
- [x] O total real contém total menos juros

### Entity\Order\Payer\Payer


- [x] Cada pagador possui endereco de cobrança como objeto
- [x] Cada pagador possui colecao de telefones
- [x] Cada pagador possui objeto pessoa fisica
- [x] Cada pagador possui objeto pessoa juridica

### Entity\Order\Products\Products


- [x] Cada produto e um objeto

### Entity\Order\Status\Status


- [x] Cada status pode ser impresso como string
- [x] Cada status possui objeto invoiced
- [x] Cada status possui objeto shipped
- [x] Cada status possui objeto shipment exception
- [x] Cada status possui objeto delivered
- [x] Cada status possui objeto unavailable
- [x] Falha ao marcar como faturado sem possuir objeto invoiced valido
- [x] Sucesso ao marcar como faturado informando objeto invoiced valido
- [x] Falha ao marcar como remetido sem possuir objeto shipped valido
- [x] Sucesso ao marcar como remetido informando objeto shipped valido
- [x] Falha ao marcar como falha na entrega sem possuir objeto shipment exception valido
- [x] Sucesso ao marcar como falha na entrega informando objeto shipment exception valido
- [x] Falha ao marcar como entregue sem possuir objeto delivered valido
- [x] Sucesso ao marcar como entregue informando objeto delivered valido
- [x] Falha ao marcar como indisponivel sem possuir objeto unavailable valido
- [x] Sucesso ao marcar como indisponivel informando objeto unavailable valido

### Entity\Product\Manager


- [x] Obtem lista de produtos cadastrados
- [x] Recupera informacoes de um pedido especifico
- [x] Gerencia update

### Entity\Product\Product


- [x] Possui propriedades e objetos
- [x] Possui nbm formatado
- [x] Possui preco formatado
- [x] Possui uma colecao de skus
- [x] Possui objeto manufacturer
- [x] Entrega json

### Entity\Product\Sku\Manager


- [x] Acesso a lista de skus cadastrados
- [x] Acessa a informacoes de um sku
- [x] Gerencia atualizacoes

### Entity\Product\Sku\Sku


- [x] Envia dados opcionais apenas se preenchidos
- [x] Possui propriedade contendo url da imagem
- [x] Sku possui objeto status
- [x] Sku possui objeto stock


### Entity\Product\Sku\Price


- [x] Possui preço normal
- [x] Possui preço com desconto

### Factory


- [x] Centraliza acesso a managers
- [x] Centraliza criacao de objetos
