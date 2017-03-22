
<!-- main -->

# submarino-sdk

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

[![Paypal Donations](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EK6F2WRKG7GNN&item_name=submarino-sdk)

<!-- require -->

<!-- require -->

## Requisitos para uso

* PHP >= *5.6*
* [curl extension](http://php.net/manual/en/intro.curl.php)
* [Composer Dependency Manager](http://getcomposer.org)

Este componente **não é uma aplicação Stand Alone** e seu objetivo é ser utilizado como biblioteca.
Sua implantação deve ser feita por desenvolvedores experientes.

**Isto não é um Plugin!**

As opções que funcionam no modo de comando apenas servem para depuração em modo de
desenvolvimento.

A documentação mais importante está nos testes unitários. Se você não consegue ler os testes unitários, eu recomendo que não utilize esta biblioteca.


<!-- //require -->

<!-- license -->


<!-- licence -->

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

<!-- //licence -->

<!-- QA -->

<!-- qa -->

---

## Indicadores de qualidade

[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/?branch=master)
[![Code Climate](https://codeclimate.com/github/gpupo/submarino-sdk/badges/gpa.svg)](https://codeclimate.com/github/gpupo/submarino-sdk)
[![Test Coverage](https://codeclimate.com/github/gpupo/submarino-sdk/badges/coverage.svg)](https://codeclimate.com/github/gpupo/submarino-sdk/coverage)


[![SensioLabsInsight](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49/big.png)](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49)

<!-- thanks -->


<!-- install -->

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

---

## Propriedades dos objetos

<!-- todo -->


<!-- dev-common -->


---

## Propriedades dos objetos

<!-- testdox -->


### SubmarinoSdk\Client


- [x] Gerencia uri de recurso
- [ ] Acesso a lista de pedidos
- [ ] Acesso a lista de produtos
- [ ] Acesso a lista de skus
- [ ] Retorna informacoes do sku informado
- [ ] Atualiza estoque do sku informado
- [ ] Atualiza preco do sku informado

### SubmarinoSdk\Entity\Order\Customer\Customer


- [x] Cada cliente possui endereco de entrega como objeto
- [x] Cada cliente possui colecao de telefones
- [x] Cada cliente possui objeto pessoa fisica
- [x] Cada cliente possui objeto pessoa juridica
- [x] Possui método ``getPf()`` para acessar Pf 
- [x] Possui método ``setPf()`` que define Pf 
- [x] Possui método ``getPj()`` para acessar Pj 
- [x] Possui método ``setPj()`` que define Pj 
- [x] Possui método ``getTelephones()`` para acessar Telephones 
- [x] Possui método ``setTelephones()`` que define Telephones 
- [x] Possui método ``getDeliveryAddress()`` para acessar DeliveryAddress 
- [x] Possui método ``setDeliveryAddress()`` que define DeliveryAddress 

### SubmarinoSdk\Entity\Order\Customer\Telephones\Telephones


- [x] Cada cliente possui colecao de telefones

### SubmarinoSdk\Entity\Order\Manager


- [x] Obtem lista pedidos
- [x] Obtém a lista de pedidos recém aprovados e que esperam processamento
- [x] Recupera informacoes de um pedido especifico
- [x] Atualiza status de um pedido
- [x] Atualiza dados de envio de um pedido
- [x] Atualiza dados de entrega de um pedido

### SubmarinoSdk\Entity\Order\Order


- [x] Cada item de uma lista e um objeto
- [x] Cada pedido possui objeto cliente
- [x] Cada pedido possui objeto com dados de cobrança
- [x] Cada pedido possui colecao de produtos
- [x] Cada pedido possui colecao de metodos de pagamento
- [x] Cada pedido possui objeto com dados de entrega
- [x] Cada pedido possui objeto status 
- [x] Possui loja de origem
- [x] Possui valor total do pedido
- [x] Possui valor total do frete
- [x] Possui valor total de desconto
- [x] Possui valor total de juros
- [x] Possui valor total do pedido descontado juros
- [x] O total real contém produtos somado a frete menos o desconto
- [x] O total real possui mesmo valor de total amount se não houver juros
- [x] O total real contém total menos juros
- [x] Possui método ``setId()`` que define Id 
- [x] Possui método ``getSiteId()`` para acessar SiteId 
- [x] Possui método ``setSiteId()`` que define SiteId 
- [x] Possui método ``getStore()`` para acessar Store 
- [x] Possui método ``setStore()`` que define Store 
- [x] Possui método ``getPurchaseDate()`` para acessar PurchaseDate 
- [x] Possui método ``setPurchaseDate()`` que define PurchaseDate 
- [x] Possui método ``getLastUpdate()`` para acessar LastUpdate 
- [x] Possui método ``setLastUpdate()`` que define LastUpdate 
- [x] Possui método ``getPurchaseTimestamp()`` para acessar PurchaseTimestamp 
- [x] Possui método ``setPurchaseTimestamp()`` que define PurchaseTimestamp 
- [x] Possui método ``getLastUpdateTimestamp()`` para acessar LastUpdateTimestamp 
- [x] Possui método ``setLastUpdateTimestamp()`` que define LastUpdateTimestamp 
- [x] Possui método ``getStatus()`` para acessar Status 
- [x] Possui método ``setStatus()`` que define Status 
- [x] Possui método ``getInvoiced()`` para acessar Invoiced 
- [x] Possui método ``setInvoiced()`` que define Invoiced 
- [x] Possui método ``getEstimatedDeliveryDate()`` para acessar EstimatedDeliveryDate 
- [x] Possui método ``setEstimatedDeliveryDate()`` que define EstimatedDeliveryDate 
- [x] Possui método ``getCustomer()`` para acessar Customer 
- [x] Possui método ``setCustomer()`` que define Customer 
- [x] Possui método ``getPayer()`` para acessar Payer 
- [x] Possui método ``setPayer()`` que define Payer 
- [x] Possui método ``getTotalAmount()`` para acessar TotalAmount 
- [x] Possui método ``setTotalAmount()`` que define TotalAmount 
- [x] Possui método ``getTotalFreight()`` para acessar TotalFreight 
- [x] Possui método ``setTotalFreight()`` que define TotalFreight 
- [x] Possui método ``getTotalDiscount()`` para acessar TotalDiscount 
- [x] Possui método ``setTotalDiscount()`` que define TotalDiscount 
- [x] Possui método ``getTotalInterest()`` para acessar TotalInterest 
- [x] Possui método ``setTotalInterest()`` que define TotalInterest 
- [x] Possui método ``getProducts()`` para acessar Products 
- [x] Possui método ``setProducts()`` que define Products 
- [x] Possui método ``getShipping()`` para acessar Shipping 
- [x] Possui método ``setShipping()`` que define Shipping 
- [x] Possui método ``getPaymentMethods()`` para acessar PaymentMethods 
- [x] Possui método ``setPaymentMethods()`` que define PaymentMethods 

### SubmarinoSdk\Entity\Order\Payer\Payer


- [x] Cada pagador possui endereco de cobrança como objeto
- [x] Cada pagador possui colecao de telefones
- [x] Cada pagador possui objeto pessoa fisica
- [x] Cada pagador possui objeto pessoa juridica

### SubmarinoSdk\Entity\Order\PaymentMethods\PaymentMethod


- [x] Cada pedido possui uma coleção de objetos payment method
- [x] Possui método ``getSequential()`` para acessar Sequential 
- [x] Possui método ``setSequential()`` que define Sequential 
- [x] Possui método ``getId()`` para acessar Id 
- [x] Possui método ``setId()`` que define Id 
- [x] Possui método ``getValue()`` para acessar Value 
- [x] Possui método ``setValue()`` que define Value 

### SubmarinoSdk\Entity\Order\Products\Product


- [x] Cada pedido possui uma coleção de objetos produto
- [x] Possui método ``getLink()`` para acessar Link 
- [x] Possui método ``setLink()`` que define Link 
- [x] Possui método ``getQuantity()`` para acessar Quantity 
- [x] Possui método ``setQuantity()`` que define Quantity 
- [x] Possui método ``getPrice()`` para acessar Price 
- [x] Possui método ``setPrice()`` que define Price 
- [x] Possui método ``getFreight()`` para acessar Freight 
- [x] Possui método ``setFreight()`` que define Freight 
- [x] Possui método ``getDiscount()`` para acessar Discount 
- [x] Possui método ``setDiscount()`` que define Discount 

### SubmarinoSdk\Entity\Order\Shipping


- [x] Possui método ``getShippingEstimateId()`` para acessar ShippingEstimateId 
- [x] Possui método ``setShippingEstimateId()`` que define ShippingEstimateId 
- [x] Possui método ``getShippingMethodId()`` para acessar ShippingMethodId 
- [x] Possui método ``setShippingMethodId()`` que define ShippingMethodId 
- [x] Possui método ``getShippingMethodName()`` para acessar ShippingMethodName 
- [x] Possui método ``setShippingMethodName()`` que define ShippingMethodName 
- [x] Possui método ``getCalculationType()`` para acessar CalculationType 
- [x] Possui método ``setCalculationType()`` que define CalculationType 
- [x] Possui método ``getShippingMethodDisplayName()`` para acessar ShippingMethodDisplayName 
- [x] Possui método ``setShippingMethodDisplayName()`` que define ShippingMethodDisplayName 

### SubmarinoSdk\Entity\Order\Status\Status


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

### SubmarinoSdk\Entity\Product\Manager


- [x] Obtem lista de produtos cadastrados
- [x] Recupera informacoes de um produto especifico
- [x] Gerencia update

### SubmarinoSdk\Entity\Product\Product


- [x] Possui propriedades e objetos 
- [x] Possui nbm formatado 
- [x] Possui preco formatado 
- [x] Possui uma colecao de skus 
- [x] Possui objeto manufacturer 
- [x] Entrega json 

### SubmarinoSdk\Entity\Product\Sku\Manager


- [x] Acesso a lista de skus cadastrados
- [x] Acessa a informacoes de um sku

### SubmarinoSdk\Entity\Product\Sku\Price


- [x] Possui preço normal
- [x] Possui preço com desconto

### SubmarinoSdk\Entity\Product\Sku\Sku


- [x] Envia dados opcionais apenas se preenchidos
- [x] Possui propriedade contendo url da imagem
- [x] Sku possui objeto status
- [x] Sku possui objeto stock

### SubmarinoSdk\Factory


- [x] Centraliza acesso a managers 
- [x] Centraliza criacao de objetos 


<!-- libraries-table -->


## Lista de dependências (libraries)

Name | Version | Description
-----|---------|------------------------------------------------------
codeclimate/php-test-reporter | v0.4.4 | PHP client for reporting test coverage to Code Climate
doctrine/instantiator | 1.0.5 | A small, lightweight utility to instantiate objects in PHP without invoking their constructors
gpupo/cache | 1.3.0 | Caching library that implements PSR-6
gpupo/common | 1.7.6 | Common Objects
gpupo/common-sdk | 2.2.15 | Componente de uso comum entre SDKs para integração a partir de aplicações PHP com Restful webservices
guzzle/guzzle | v3.9.3 | PHP HTTP client. This library is deprecated in favor of https://packagist.org/packages/guzzlehttp/guzzle
monolog/monolog | 1.22.1 | Sends your logs to files, sockets, inboxes, databases and various web services
myclabs/deep-copy | 1.6.0 | Create deep copies (clones) of your objects
padraic/humbug_get_contents | 1.0.4 | Secure wrapper for accessing HTTPS resources with file_get_contents for PHP 5.3+
padraic/phar-updater | 1.0.3 | A thing to make PHAR self-updating easy and secure.
phpdocumentor/reflection-common | 1.0 | Common reflection classes used by phpdocumentor to reflect the code structure
phpdocumentor/reflection-docblock | 3.1.1 | With this component, a library can provide support for annotations via DocBlocks or otherwise retrieve information that is embedded in a DocBlock.
phpdocumentor/type-resolver | 0.2.1 | 
phpspec/prophecy | v1.7.0 | Highly opinionated mocking framework for PHP 5.3+
phpunit/php-code-coverage | 4.0.7 | Library that provides collection, processing, and rendering functionality for PHP code coverage information.
phpunit/php-file-iterator | 1.4.2 | FilterIterator implementation that filters files based on a list of suffixes.
phpunit/php-text-template | 1.2.1 | Simple template engine.
phpunit/php-timer | 1.0.9 | Utility class for timing
phpunit/php-token-stream | 1.4.11 | Wrapper around PHP's tokenizer extension.
phpunit/phpunit | 5.7.17 | The PHP Unit Testing framework.
phpunit/phpunit-mock-objects | 3.4.3 | Mock Object library for PHPUnit
psr/cache | 1.0.0 | Common interface for caching libraries
psr/log | 1.0.2 | Common interface for logging libraries
satooshi/php-coveralls | v1.0.1 | PHP client library for Coveralls API
sebastian/code-unit-reverse-lookup 1.0.1 | Looks up which function or method a line of code belongs to
sebastian/comparator | 1.2.4 | Provides the functionality to compare PHP values for equality
sebastian/diff | 1.4.1 | Diff implementation
sebastian/environment | 2.0.0 | Provides functionality to handle HHVM/PHP environments
sebastian/exporter | 2.0.0 | Provides the functionality to export PHP variables for visualization
sebastian/global-state | 1.1.1 | Snapshotting of global state
sebastian/object-enumerator | 2.0.1 | Traverses array structures and object graphs to enumerate all referenced objects
sebastian/peek-and-poke | dev-master a8295 | Proxy for accessing non-public attributes and methods of an object
sebastian/recursion-context | 2.0.0 | Provides functionality to recursively process PHP variables
sebastian/resource-operations | 1.0.0 | Provides a list of PHP built-in functions that operate on resources
sebastian/version | 2.0.1 | Library that helps with managing the version number of Git-hosted PHP projects
symfony/config | v3.2.6 | Symfony Config Component
symfony/console | v3.2.6 | Symfony Console Component
symfony/debug | v3.2.6 | Symfony Debug Component
symfony/event-dispatcher | v2.8.18 | Symfony EventDispatcher Component
symfony/filesystem | v3.2.6 | Symfony Filesystem Component
symfony/polyfill-mbstring | v1.3.0 | Symfony polyfill for the Mbstring extension
symfony/stopwatch | v3.2.6 | Symfony Stopwatch Component
symfony/yaml | v3.2.6 | Symfony Yaml Component
twig/twig | v2.3.0 | Twig, the flexible, fast, and secure template language for PHP
webmozart/assert | 1.2.0 | Assertions to validate method input/output with nice error messages.



<!-- footer-common -->


