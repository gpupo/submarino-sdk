<!-- install -->

---

## Instalação

Adicione o pacote [submarino-sdk](https://packagist.org/packages/gpupo/submarino-sdk) ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/submarino-sdk

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

$manager = $submarinoSdk->factoryManager('product'));
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

```

### Acesso a informações de um produto específico

``` PHP
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
