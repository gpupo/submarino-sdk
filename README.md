[![Build Status](https://secure.travis-ci.org/gpupo/submarino-sdk.png?branch=master)](http://travis-ci.org/gpupo/submarino-sdk)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gpupo/submarino-sdk/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49/mini.png)](https://insight.sensiolabs.com/projects/baf451b6-4c13-4e84-ae29-c7db67c38b49)
[![Code Climate](https://codeclimate.com/github/gpupo/submarino-sdk/badges/gpa.svg)](https://codeclimate.com/github/gpupo/submarino-sdk)
[![Test Coverage](https://codeclimate.com/github/gpupo/submarino-sdk/badges/coverage.svg)](https://codeclimate.com/github/gpupo/submarino-sdk/coverage)
[![Codacy Badge](https://www.codacy.com/project/badge/42d610984533411e9ee267c00106c38d)](https://www.codacy.com/app/g/submarino-sdk)

SDK Não Oficial para integração a partir de aplicações PHP com as APIs da B2W Marketplace (Submarino, Shoptime, Americanas.com)

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

### Uso

Este exemplo demonstra o uso simplificado a partir do ``Factory``:


    <?php
    ///...
    use Gpupo\SubmarinoSdk\Factory;

    $submarinoSdk = Factory::getInstance()->setup(['token' => '7Ao82svbm#6', 'version' => 'sandbox']);

    $manager = $submarinoSdk->factoryManager('product'));

    // Acesso a lista de produtos cadastrados:
    $produtosCadastrados = $manager->fetch(); // Collection de Objetos Product

    // Acesso a informações de um produto cadastrado e com identificador conhecido:
    $produto = $manager->findById(9)); // Objeto Produto
    echo $product->getName(); // Acesso ao nome do produto de Id 9


    // Criação de um produto:
    $data = []; // Veja o formato de $data em Resources/fixture/Products.json
    $product = $submarinoSdk->createProduct($data);

    foreach ($data['sku'] as $item) {
        $sku = $submarinoSdk->createSku($item);
        $product->getSku()->add($sku);
    }

    $manager->save($product);



### Exemplos de manutenção de Produtos


    <?php
    // ...

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

### Exemplos de manutenção de Pedidos

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


#### Uso de cache para otimização de updates

    <?php

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

----

* [Documentação oficial](https://api-sandbox.bonmarketplace.com.br/docs/)

## Licença

MIT, see [LICENSE](https://github.com/gpupo/submarino-sdk/blob/master/LICENSE).

---

## Contributors

- [@gpupo](https://github.com/gpupo)
- [@danielcosta](https://github.com/danielcosta)
- [All Contributors](https://github.com/gpupo/submarino-sdk/contributors)

---

## Desenvolvimento

    git clone --depth=1  git@github.com:gpupo/submarino-sdk.git

    cd submarino-sdk;

    composer install;

Personalize a configuração do ``phpunit``:

    cp phpunit.xml.dist phpunit.xml;

Insira sua Token de Sandbox em ``phpunit.xml``:

    <!-- Customize your parameters ! -->
    <php>
        <const name="API_TOKEN" value=""/>
        <const name="VERBOSE" value="false"/>
    </php>

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

### Entity\Order\Payer\Payer

- Cada pagador possui endereco de cobrança como objeto
- Cada pagador possui colecao de telefones
- Cada pagador possui objeto pessoa fisica
- Cada pagador possui objeto pessoa juridica

### Entity\Order\Products\Products

- Cada pedido possui uma coleção de objetos produto

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
