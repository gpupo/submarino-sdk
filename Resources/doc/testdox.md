
### SubmarinoSdk\Client


- [x] Gerencia uri de recurso
- [ ] Acesso a lista de pedidos
- [ ] Acesso a lista de produtos
- [ ] Acesso a lista de skus

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
- [x] Entidade é uma Coleção 

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
- [x] Entidade é uma Coleção 

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
- [x] Entidade é uma Coleção 

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
- [x] Entidade é uma Coleção 

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
- [x] Entidade é uma Coleção 

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

