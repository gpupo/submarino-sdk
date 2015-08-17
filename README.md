[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/?branch=master)
[![Code Climate](https://codeclimate.com/github/gpupo/submarino-sdk/badges/gpa.svg)](https://codeclimate.com/github/gpupo/submarino-sdk)
[![Test Coverage](https://codeclimate.com/github/gpupo/submarino-sdk/badges/coverage.svg)](https://codeclimate.com/github/gpupo/submarino-sdk/coverage)

# submarino-sdk

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

---

## Requisitos

* PHP >= *5.4*
* [curl extension](http://php.net/manual/en/intro.curl.php)
* [Composer Dependency Manager](http://getcomposer.org)

---

## Instalação

Adicione o pacote [submarino-sdk](https://packagist.org/packages/gpupo/submarino-sdk) ao seu projeto utilizando [composer](http://getcomposer.org):

    composer require gpupo/submarino-sdk

---

### Comandos

Lista de comandos disponíveis:

    ./bin/main

Você pode verificar suas credenciais Cnova na linha de comando:

    ./bin/main credential

---

# Uso

## Setup

Este exemplo demonstra o uso simplificado a partir do ``Factory``:

``` PHP

use Gpupo\SubmarinoSdk\Factory;

$submarinoSdk = Factory::getInstance()->setup([
    'token'     => '7Ao82svbm#6',
    'version'   => 'sandbox',
]);

```

## Acesso a lista de produtos cadastrados

``` PHP

$manager = $submarinoSdk->factoryManager('product'));
$produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

```

## Acesso a informações de um produto específico

``` PHP
$produto = $manager->findById(9));
echo $product->getName();

```

## Criação de um produto

Veja o formato de ``$data`` em Resources/fixture/Products.json

``` PHP

$data = [];
$product = $submarinoSdk->createProduct($data);

foreach ($data['sku'] as $item) {
    $sku = $submarinoSdk->createSku($item);
    $product->getSku()->add($sku);
}

$manager->save($product);

```

## Exemplos de manutenção de Produtos

``` PHP

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

``` PHP

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

``` PHP

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
----

* [Documentação oficial](https://api-sandbox.bonmarketplace.com.br/docs/)

## Licença

MIT, see [LICENSE](https://github.com/gpupo/submarino-sdk/blob/master/LICENSE).

---

## Contributors

- [@gpupo](https://github.com/gpupo)
- [All Contributors](https://github.com/gpupo/submarino-sdk/contributors)

---

## Desenvolvimento

    git clone --depth=1  git@github.com:gpupo/submarino-sdk.git

    cd submarino-sdk;

    composer install;

Personalize a configuração do ``phpunit``:

    cp phpunit.xml.dist phpunit.xml;

Insira sua Token de Sandbox em ``phpunit.xml``:

``` XML

    <!-- Customize your parameters ! -->
    <php>
        <const name="API_TOKEN" value=""/>
        <const name="VERBOSE" value="false"/>
    </php>

```

Rode os testes localmente:

    $ phpunit

---

## Links

* [Submarino-sdk Composer Package](https://packagist.org/packages/gpupo/submarino-sdk) no packagist.org
* [marketplace-bundle Composer Package](https://packagist.org/packages/gpupo/marketplace-bundle) - Integração deste pacote com Symfony2
* [Outras SDKs para o Ecommerce do Brasil](https://github.com/gpupo/common-sdk)

---

## Propriedades dos objetos

A lista abaixo é gerada a partir da saída da execução dos testes unitários:
<!--
phpunit --testdox | grep -vi php |  sed "s/.*\[*]/-/" | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/.*Gpupo.*/&\'$'\n/g' | sed 's/Gpupo\\Tests\\SubmarinoSdk\\/### /g' | sed '/./,/^$/!d' >> README.md
-->

### Client

- Gerencia uri de recurso
- Acesso a lista de pedidos
- Acesso a lista de produtos
- Acesso a lista de skus

### Entity\Order\Customer\Customer

- Cada cliente possui endereco de entrega como objeto
- Cada cliente possui colecao de telefones
- Cada cliente possui objeto pessoa fisica
- Cada cliente possui objeto pessoa juridica
- Possui método ``getPf()`` para acessar Pf
- Possui método ``setPf()`` que define Pf
- Possui método ``getPj()`` para acessar Pj
- Possui método ``setPj()`` que define Pj
- Possui método ``getTelephones()`` para acessar Telephones
- Possui método ``setTelephones()`` que define Telephones
- Possui método ``getDeliveryAddress()`` para acessar DeliveryAddress
- Possui método ``setDeliveryAddress()`` que define DeliveryAddress
- Entidade é uma Coleção

### Entity\Order\Customer\Telephones\Telephones

- Cada cliente possui colecao de telefones

### Entity\Order\Manager

- Obtem lista pedidos
- Obtém a lista de pedidos recém aprovados e que esperam processamento
- Recupera informacoes de um pedido especifico
- Atualiza status de um pedido
- Atualiza dados de envio de um pedido
- Atualiza dados de entrega de um pedido

### Entity\Order\Order

- Cada item de uma lista e um objeto
- Cada pedido possui objeto cliente
- Cada pedido possui objeto com dados de cobrança
- Cada pedido possui colecao de produtos
- Cada pedido possui colecao de metodos de pagamento
- Cada pedido possui objeto com dados de entrega
- Cada pedido possui objeto status
- Possui loja de origem
- Possui valor total do pedido
- Possui valor total do frete
- Possui valor total de desconto
- Possui valor total de juros
- Possui valor total do pedido descontado juros
- O total real contém produtos somado a frete menos o desconto
- O total real possui mesmo valor de total amount se não houver juros
- O total real contém total menos juros
- Possui método ``setId()`` que define Id
- Possui método ``getSiteId()`` para acessar SiteId
- Possui método ``setSiteId()`` que define SiteId
- Possui método ``getStore()`` para acessar Store
- Possui método ``setStore()`` que define Store
- Possui método ``getPurchaseDate()`` para acessar PurchaseDate
- Possui método ``setPurchaseDate()`` que define PurchaseDate
- Possui método ``getLastUpdate()`` para acessar LastUpdate
- Possui método ``setLastUpdate()`` que define LastUpdate
- Possui método ``getPurchaseTimestamp()`` para acessar PurchaseTimestamp
- Possui método ``setPurchaseTimestamp()`` que define PurchaseTimestamp
- Possui método ``getLastUpdateTimestamp()`` para acessar LastUpdateTimestamp
- Possui método ``setLastUpdateTimestamp()`` que define LastUpdateTimestamp
- Possui método ``getStatus()`` para acessar Status
- Possui método ``setStatus()`` que define Status
- Possui método ``getInvoiced()`` para acessar Invoiced
- Possui método ``setInvoiced()`` que define Invoiced
- Possui método ``getEstimatedDeliveryDate()`` para acessar EstimatedDeliveryDate
- Possui método ``setEstimatedDeliveryDate()`` que define EstimatedDeliveryDate
- Possui método ``getCustomer()`` para acessar Customer
- Possui método ``setCustomer()`` que define Customer
- Possui método ``getPayer()`` para acessar Payer
- Possui método ``setPayer()`` que define Payer
- Possui método ``getTotalAmount()`` para acessar TotalAmount
- Possui método ``setTotalAmount()`` que define TotalAmount
- Possui método ``getTotalFreight()`` para acessar TotalFreight
- Possui método ``setTotalFreight()`` que define TotalFreight
- Possui método ``getTotalDiscount()`` para acessar TotalDiscount
- Possui método ``setTotalDiscount()`` que define TotalDiscount
- Possui método ``getTotalInterest()`` para acessar TotalInterest
- Possui método ``setTotalInterest()`` que define TotalInterest
- Possui método ``getProducts()`` para acessar Products
- Possui método ``setProducts()`` que define Products
- Possui método ``getShipping()`` para acessar Shipping
- Possui método ``setShipping()`` que define Shipping
- Possui método ``getPaymentMethods()`` para acessar PaymentMethods
- Possui método ``setPaymentMethods()`` que define PaymentMethods
- Entidade é uma Coleção

### Entity\Order\Payer\Payer

- Cada pagador possui endereco de cobrança como objeto
- Cada pagador possui colecao de telefones
- Cada pagador possui objeto pessoa fisica
- Cada pagador possui objeto pessoa juridica

### Entity\Order\PaymentMethods\PaymentMethod

- Cada pedido possui uma coleção de objetos payment method
- Possui método ``getSequential()`` para acessar Sequential
- Possui método ``setSequential()`` que define Sequential
- Possui método ``getId()`` para acessar Id
- Possui método ``setId()`` que define Id
- Possui método ``getValue()`` para acessar Value
- Possui método ``setValue()`` que define Value
- Entidade é uma Coleção

### Entity\Order\Products\Product

- Cada pedido possui uma coleção de objetos produto
- Possui método ``getLink()`` para acessar Link
- Possui método ``setLink()`` que define Link
- Possui método ``getQuantity()`` para acessar Quantity
- Possui método ``setQuantity()`` que define Quantity
- Possui método ``getPrice()`` para acessar Price
- Possui método ``setPrice()`` que define Price
- Possui método ``getFreight()`` para acessar Freight
- Possui método ``setFreight()`` que define Freight
- Possui método ``getDiscount()`` para acessar Discount
- Possui método ``setDiscount()`` que define Discount
- Entidade é uma Coleção

### Entity\Order\Shipping

- Possui método ``getShippingEstimateId()`` para acessar ShippingEstimateId
- Possui método ``setShippingEstimateId()`` que define ShippingEstimateId
- Possui método ``getShippingMethodId()`` para acessar ShippingMethodId
- Possui método ``setShippingMethodId()`` que define ShippingMethodId
- Possui método ``getShippingMethodName()`` para acessar ShippingMethodName
- Possui método ``setShippingMethodName()`` que define ShippingMethodName
- Possui método ``getCalculationType()`` para acessar CalculationType
- Possui método ``setCalculationType()`` que define CalculationType
- Possui método ``getShippingMethodDisplayName()`` para acessar ShippingMethodDisplayName
- Possui método ``setShippingMethodDisplayName()`` que define ShippingMethodDisplayName
- Entidade é uma Coleção

### Entity\Order\Status\Status

- Cada status pode ser impresso como string
- Cada status possui objeto invoiced
- Cada status possui objeto shipped
- Cada status possui objeto shipment exception
- Cada status possui objeto delivered
- Cada status possui objeto unavailable
- Falha ao marcar como faturado sem possuir objeto invoiced valido
- Sucesso ao marcar como faturado informando objeto invoiced valido
- Falha ao marcar como remetido sem possuir objeto shipped valido
- Sucesso ao marcar como remetido informando objeto shipped valido
- Falha ao marcar como falha na entrega sem possuir objeto shipment exception valido
- Sucesso ao marcar como falha na entrega informando objeto shipment exception valido
- Falha ao marcar como entregue sem possuir objeto delivered valido
- Sucesso ao marcar como entregue informando objeto delivered valido
- Falha ao marcar como indisponivel sem possuir objeto unavailable valido
- Sucesso ao marcar como indisponivel informando objeto unavailable valido

### Entity\Product\Manager

- Obtem lista de produtos cadastrados
- Recupera informacoes de um produto especifico
- Gerencia update

### Entity\Product\Product

- Possui propriedades e objetos
- Possui nbm formatado
- Possui preco formatado
- Possui uma colecao de skus
- Possui objeto manufacturer
- Entrega json

### Entity\Product\Sku\Manager

- Acesso a lista de skus cadastrados
- Acessa a informacoes de um sku

### Entity\Product\Sku\Price

- Possui preço normal
- Possui preço com desconto

### Entity\Product\Sku\Sku

- Envia dados opcionais apenas se preenchidos
- Possui propriedade contendo url da imagem
- Sku possui objeto status
- Sku possui objeto stock

### Factory

- Centraliza acesso a managers
- Centraliza criacao de objetos
