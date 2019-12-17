<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\SubmarinoSdk\Console\Command\Homologation;

use Gpupo\SubmarinoSdk\Console\Command\AbstractCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends AbstractCommand
{
    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const DELETE = 'delete';
    const SKU_NUMBER_SIZE = 5;
    const REMOTE_IMAGE_FIXTURE = 'https://opensource.gpupo.com/submarino-sdk/assets/111c8527.JPG';

    private $skus = [];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName(self::prefix.'homologation:run')
            ->setDescription('Run homologation process');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info> > Iniciando...</>');
        $this->reset();

        $output->writeln('<info> > Processando...</>');
        $result = [];
        foreach ($this->getProcessSteps() as $group => $steps) {
            foreach ($steps as $key => $step) {
                if (!method_exists($this, $step)) {
                    $result[] = [$step, 'NOT IMPLEMENTED'];

                    continue;
                }

                $return = $this->{$step}();
                $result[] = [$return['description'], $return['status'] ? '<bg=green>'.$return['detail'].'</>' : '<bg=red>FAIL</>'];
            }

            $result[] = new TableSeparator();
        }

        $result[] = ['******* Simulação de homologação executada com sucesso *******'];

        $resultTable = new Table($output);
        $resultTable->setStyle('box-double');
        $resultTable->setHeaders(['Ação', 'Resultado'])->setRows($result);
        $resultTable->render();
    }

    protected function getProcessSteps()
    {
        return [
            'product' => [
                'createSimpleProduct',
                'createProductWithVariation',
                'createProductWithMoreVariation',
                'createProductWithMoreVariationAndPriceVariation',
                'createProductWithVariationAndSizeAttribute',
                'createProductWithVariationAndColorAttribute',
                'createProductWithVariationAndColorAndSizeAttributes',
                'createProductWithVariationAndCrossdockingAttribute',
                'createProductWithCategoryAndSubcategory',
                'updateProductStock',
                'updateProductPrice',
                'enableProduct',
                'disableProduct',
                'removeProduct',
            ],
            'order' => [
                'createOrderWithSimpleProduct',
                'createOrderWithSimpleProductAndQuantityGranThan1',
                'createOrderWithProductVariation',
                'createOrderWithProductVariationAndQuantityGranThan1',
                'updateOrderStatusForInvoiced',
                'updateOrderStatusForShippedWithTrackingNumberAndInvoice',
                'updateOrderStatusForDeliveredWithTrackingNumberAndInvoice',
                'updateOrderStatusForCanceled',
                'updateOrderStatusForDeliveryException',
                'fetchOrderQueue',
            ],
            'plp' => [
                'agruparPlpEntregasCorreios',
                'recuperarPlpCorreios',
                'agruparPlpEntregasDirect',
                'recuperarPlpDirect',
                'confirmarColetaPlpDirect',
            ],
        ];
    }

    protected function request($method, $resource, $body = null)
    {
        switch ($method) {
            case self::GET:
                return $this->getFactory()->getClient()->get($resource, null);

                break;
            case self::POST:
                return $this->getFactory()->getClient()->post($resource, $body);

                break;
            case self::PUT:
                return $this->getFactory()->getClient()->put($resource, $body);

                break;
            case self::DELETE:
                return $this->getFactory()->getClient()->delete($resource, null);

                break;
            default:
                return false;
        }
    }

    protected function fetchOrder()
    {
        try {
            $response = $this->request(self::GET, '/queues/orders');
            $code = $response->getData()['code'];
            $this->request(self::DELETE, "/queues/orders/${code}");
        } catch (\Exception $e) {
        }

        return $code ?? false;
    }

    protected function getNextSku()
    {
        if (null === $this->skus) {
            $this->skus = [];
        }

        $nextSku = 'H'.str_pad(sprintf('%d', \count($this->skus) + 1), self::SKU_NUMBER_SIZE, '0', STR_PAD_LEFT);
        $this->skus[] = $nextSku;

        return $nextSku;
    }

    protected function reset()
    {
        do {
            try {
                $response = $this->request(self::GET, '/queues/orders');
                $code = $response->getData()['code'];
                $response = $this->request(self::DELETE, "/queues/orders/${code}");
            } catch (\Exception $e) {
            }
        } while (!empty($response->getResponseRaw()));

        $attempt = 0;
        do {
            try {
                $response = $this->request(self::DELETE, '/products/'.$this->getNextSku());
            } catch (\Exception $e) {
                ++$attempt;
            }
        } while (204 === $response->getHttpStatusCode() && $attempt < 50);

        $this->skus = null;
    }

    protected function getNewProduct($sku)
    {
        $product = [];
        $product['sku'] = "${sku}";
        $product['name'] = 'Kit de Juntas do Cavalete de Água';
        $product['description'] = "Aplicaçao Citroen Peugeot\r\n";
        $product['status'] = 'enabled';
        $product['qty'] = 12;
        $product['price'] = 99.46;
        $product['promotional_price'] = 99.46;
        $product['cost'] = 43.43;
        $product['weight'] = 0.5;
        $product['height'] = 50;
        $product['width'] = 50;
        $product['length'] = 50;
        $product['brand'] = 'Citroen';
        $product['ean'] = null;
        $product['nbm'] = '';
        $product['images'] = [
            $this::REMOTE_IMAGE_FIXTURE,
        ];
        $product['categories'] = [
            [
                'code' => '3637',
                'name' => 'Peças Automotivas > Motor > Jogo de Juntas > Juntas de Admissão',
            ],
        ];

        return $product;
    }

    protected function getNewProductVariation($sku, $color = 'Amarelo', $size = 'Pequeno', $promotionalPrice = 99.46, $crossDocking = null)
    {
        $productVariation = [];
        $productVariation['sku'] = "${sku}";
        $productVariation['qty'] = 10;
        $productVariation['ean'] = null;
        $productVariation['images'] = [
            $this::REMOTE_IMAGE_FIXTURE,
        ];
        $productVariation['specifications'] = [
            [
                'key' => 'Cor',
                'value' => "{$color}",
            ],
            [
                'key' => 'Tamanho',
                'value' => "{$size}",
            ],
            [
                'key' => 'promotional_price',
                'value' => "{$promotionalPrice}",
            ],
            [
                'key' => 'crossDocking',
                'value' => "{$crossDocking}",
            ],
        ];

        return $productVariation;
    }

    protected function getNewOrder()
    {
        $order = [];
        $order = [
            'channel' => 'loja-de-pecas',
            'items' => [
                [
                    'id' => 'H0001',
                    'qty' => 2,
                    'original_price' => 99.46,
                    'special_price' => 99.46,
                ],
            ],
            'customer' => [
                'name' => 'Nome do Consumidor',
                'email' => 'comprador@exemplo.com.br',
                'date_of_birth' => '1995-01-01',
                'gender' => 'male',
                'vat_number' => '12312312309',
                'phones' => [
                    '8899999999',
                ],
            ],
            'billing_address' => [
                'street' => 'Rua de teste',
                'number' => 1234,
                'detail' => 'Ponto de referência teste',
                'neighborhood' => 'Bairro teste',
                'city' => 'Cidade de teste',
                'region' => 'UF',
                'country' => 'BR',
                'postcode' => '90000000',
            ],
            'shipping_address' => [
                'street' => 'Rua de teste',
                'number' => 1234,
                'detail' => 'Ponto de referência teste',
                'neighborhood' => 'Bairro teste',
                'city' => 'Cidade de teste',
                'region' => 'UF',
                'country' => 'BR',
                'postcode' => '90000000',
            ],
            'shipping_method' => 'Transportadora',
            'estimated_delivery' => '2015-01-10',
            'shipping_cost' => 5.0,
            'interest' => 0.0,
            'discount' => 0.00,
        ];

        return $order;
    }

    protected function agruparPlpEntregasCorreios()
    {
        $response = $this->request(self::GET, '/shipments/b2w/to_group?offset=1');
        $array = json_decode($response->getResponseRaw(), true);

        foreach ($array['orders'] as $order) {
            if ('CORREIOS' === $order['shipping']) {
                try {
                    $response = $this->request(self::POST, '/shipments/b2w/', json_encode(['order_remote_codes' => [$order['code']]]));

                    return [
                        'description' => 'Agrupar PLP B2W Entregas Correios',
                        'status' => 201 === $response->getHttpStatusCode(),
                        'detail' => $response->getResponseRaw(),
                    ];
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }
    }

    protected function recuperarPlpCorreios()
    {
        $response = $this->request(self::GET, '/shipments/b2w');
        $array = json_decode($response->getResponseRaw(), true);

        foreach ($array['plp'] as $plp) {
            if ('CORREIOS' === $plp['type']) {
                $response = $this->request(self::GET, '/shipments/b2w/view?plp_id='.$plp['id']);
                $array = json_decode($response->getResponseRaw(), true);

                return [
                    'description' => 'Recuperar PLP Correios',
                    'status' => 200 === $response->getHttpStatusCode(),
                    'detail' => $array['plp']['id'],
                ];
            }
        }
    }

    protected function agruparPlpEntregasDirect()
    {
        $response = $this->request(self::GET, '/shipments/b2w/to_group?offset=3');
        $array = json_decode($response->getResponseRaw(), true);

        foreach ($array['orders'] as $order) {
            if ('DIRECT' === $order['shipping']) {
                try {
                    $response = $this->request(self::POST, '/shipments/b2w/', json_encode(['order_remote_codes' => [$order['code']]]));

                    return [
                        'description' => 'Agrupar PLP B2W Entregas Direct',
                        'status' => 201 === $response->getHttpStatusCode(),
                        'detail' => $response->getResponseRaw(),
                    ];
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }
    }

    protected function recuperarPlpDirect()
    {
        $response = $this->request(self::GET, '/shipments/b2w');
        $array = json_decode($response->getResponseRaw(), true);

        foreach ($array['plp'] as $plp) {
            if ('DIRECT' === $plp['type']) {
                $response = $this->request(self::GET, '/shipments/b2w/view?plp_id='.$plp['id']);
                $array = json_decode($response->getResponseRaw(), true);

                return [
                    'description' => 'Recuperar PLP Direct',
                    'status' => 200 === $response->getHttpStatusCode(),
                    'detail' => $array['plp']['id'],
                ];
            }
        }
    }

    protected function confirmarColetaPlpDirect()
    {
        $response = $this->request(self::GET, '/shipments/b2w/collectables?requested=false');
        $array = json_decode($response->getResponseRaw(), true);

        foreach ($array['orders'] as $order) {
            $response = $this->request(self::POST, '/shipments/b2w/confirm_collection', json_encode(['order_codes' => [$order['code']]]));

            return [
                'description' => 'Confirmar Coleta PLP Direct',
                'status' => 201 === $response->getHttpStatusCode(),
                'detail' => sprintf('ORDER: %s', $order['code']),
            ];
        }
    }

    protected function createSimpleProduct()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto Simples',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => $sku,
        ];
    }

    protected function createProductWithVariation()
    {
        $skuParent = $this->getNextSku();
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'] = [$this->getNewProductVariation($sku)];
        $product['variation_attributes'] = ['Cor'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com uma variação',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s', $skuParent, $sku),
        ];
    }

    protected function createProductWithMoreVariation()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();
        $skuC = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Amarelo', 'Pequeno');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Azul', 'Normal');
        $product['variations'][] = $this->getNewProductVariation($skuC, 'Vermelho', 'Grande');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com mais de uma variação',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s, %s, %s', $skuParent, $skuA, $skuB, $skuC),
        ];
    }

    protected function createProductWithMoreVariationAndPriceVariation()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Amarelo', 'Pequeno', 49.99);
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Verde', 'Grande', 69.99);
        $product['variation_attributes'] = ['Cor', 'Tamanho', 'promotional_price'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com mais de uma variação e com variação de preço no sku.',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s, %s', $skuParent, $skuA, $skuB),
        ];
    }

    protected function createProductWithVariationAndSizeAttribute()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Amarelo', 'Extra Grande');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Verde', 'Gigante');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com variação contendo o atributo tamanho',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s, %s', $skuParent, $skuA, $skuB),
        ];
    }

    protected function createProductWithVariationAndColorAttribute()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Roxo', 'Pequeno');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Marrom', 'Grande');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com variação contendo o atributo cor',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s, %s', $skuParent, $skuA, $skuB),
        ];
    }

    protected function createProductWithVariationAndColorAndSizeAttributes()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Roxo', 'Pequeno');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Marrom', 'Grande');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com variações contendo os atributos cor e tamanho',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s, %s', $skuParent, $skuA, $skuB),
        ];
    }

    protected function createProductWithVariationAndCrossdockingAttribute()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Vermelho', 'Médio', null, 10);
        $product['variation_attributes'] = ['Cor', 'Tamanho', 'crossDocking'];

        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com variação contendo o atributo Crossdocking',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => sprintf('%s | %s', $skuParent, $skuA),
        ];
    }

    protected function createProductWithCategoryAndSubcategory()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $response = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        return [
            'description' => 'Criar produto com categoria (categoria + subcategoria)',
            'status' => 201 === $response->getHttpStatusCode(),
            'detail' => $sku,
        ];
    }

    protected function updateProductStock()
    {
        $quantityNew = 22;
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $quantityOld = $product['qty'];
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));
        $responseB = $this->request(self::PUT, "/products/${sku}", json_encode(['product' => ['qty' => $quantityNew]]));

        return [
            'description' => 'Atualizar estoque de produto (enviar valores antes da atualização e após a atualização)',
            'status' => 201 === $responseA->getHttpStatusCode() && 204 === $responseB->getHttpStatusCode(),
            'detail' => sprintf('%s | [qty] from: \'%d\' to \'%d\'', $sku, $quantityOld, $quantityNew),
        ];
    }

    protected function updateProductPrice()
    {
        $priceNew = 1.99;
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $priceOld = $product['price'];
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));
        $responseB = $this->request(self::PUT, "/products/${sku}", json_encode(['product' => ['promotional_price' => $priceNew]]));

        return [
            'description' => 'Atualizar preço de produto (enviar valores antes da atualização e após a atualização)',
            'status' => 201 === $responseA->getHttpStatusCode() && 204 === $responseB->getHttpStatusCode(),
            'detail' => sprintf('%s | [price] from: \'%0.2f\' to \'%0.2f\'', $sku, $priceOld, $priceNew),
        ];
    }

    protected function enableProduct()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $product['status'] = 'disabled';
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));
        $responseB = $this->request(self::PUT, "/products/${sku}", json_encode(['product' => ['status' => 'enabled']]));

        return [
            'description' => 'Alterar status do produto para \'enabled\'',
            'status' => 201 === $responseA->getHttpStatusCode() && 204 === $responseB->getHttpStatusCode(),
            'detail' => $sku,
        ];
    }

    protected function disableProduct()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));
        $responseB = $this->request(self::PUT, "/products/${sku}", json_encode(['product' => ['status' => 'disabled']]));

        return [
            'description' => 'Alterar status do produto para \'disabled\'',
            'status' => 201 === $responseA->getHttpStatusCode() && 204 === $responseB->getHttpStatusCode(),
            'detail' => $sku,
        ];
    }

    protected function removeProduct()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));
        $responseB = $this->request(self::DELETE, "/products/${sku}");

        return [
            'description' => 'Deletar um produto',
            'status' => 201 === $responseA->getHttpStatusCode() && 204 === $responseB->getHttpStatusCode(),
            'detail' => $sku,
        ];
    }

    protected function createOrderWithSimpleProduct()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();

        return [
            'description' => 'Criar pedido com produto simples',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function createOrderWithSimpleProductAndQuantityGranThan1()
    {
        $sku = $this->getNextSku();

        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 5;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();

        return [
            'description' => 'Criar pedido com produto simples e a quantidade do produto maior que 1',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function createOrderWithProductVariation()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();
        $skuC = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Amarelo', 'Pequeno');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Azul', 'Normal');
        $product['variations'][] = $this->getNewProductVariation($skuC, 'Vermelho', 'Grande');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $skuB;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();

        return [
            'description' => 'Criar pedido com produto que contenha variação',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $skuB),
        ];
    }

    protected function createOrderWithProductVariationAndQuantityGranThan1()
    {
        $skuParent = $this->getNextSku();
        $skuA = $this->getNextSku();
        $skuB = $this->getNextSku();
        $skuC = $this->getNextSku();

        $product = $this->getNewProduct($skuParent);
        $product['variations'][] = $this->getNewProductVariation($skuA, 'Amarelo', 'Pequeno');
        $product['variations'][] = $this->getNewProductVariation($skuB, 'Azul', 'Normal');
        $product['variations'][] = $this->getNewProductVariation($skuC, 'Vermelho', 'Grande');
        $product['variation_attributes'] = ['Cor', 'Tamanho'];
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $skuC;
        $order['items'][0]['qty'] = 3;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();

        return [
            'description' => 'Criar pedido com produto que contenha variação e a quantidade da mesma maior que 1',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $skuC),
        ];
    }

    protected function updateOrderStatusForInvoiced()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();
        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/approval", json_encode(['status' => 'payment_received']));

        $invoice = ['key' => '41193879763884333781553313005110611273247541'];
        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/invoice", json_encode(['status' => 'order_invoiced', 'invoice' => $invoice]));

        $lastOrder = $this->fetchOrder();

        return [
            'description' => 'Atualizar status de pedido para Faturado',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function updateOrderStatusForShippedWithTrackingNumberAndInvoice()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $lastOrder = $this->fetchOrder();
        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/approval", json_encode(['status' => 'payment_received']));

        $this->fetchOrder();
        $responseD = $this->request(self::POST, "/orders/{$lastOrder}/invoice", json_encode(
            [
            'status' => 'order_invoiced',
            'invoice' => [
                'key' => '99999999999999999999999999999999999999999999',
                ],
            ]
        ));

        $this->fetchOrder();
        $responseE = $this->request(self::POST, "/orders/{$lastOrder}/shipments", json_encode(
            [
                'status' => 'order_shipped',
                'shipment' => [
                    'code' => "{$lastOrder}",
                    'items' => [
                        [
                            'sku' => "{$sku}",
                            'qty' => 1,
                        ],
                    ],
                    'track' => [
                        'code' => 'BR1321830198302DR',
                        'carrier' => 'Correios',
                        'method' => 'SEDEX',
                        'url' => 'www.correios.com.br',
                    ],
                ],
            ]
        ));

        $this->fetchOrder();

        return [
            'description' => 'Atualizar status de pedido para enviado com código de rastreio e chave da NFE',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode()
             && 201 === $responseD->getHttpStatusCode()
             && 201 === $responseE->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function updateOrderStatusForDeliveredWithTrackingNumberAndInvoice()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));
        $lastOrder = $this->fetchOrder();

        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/approval", json_encode(['status' => 'payment_received']));
        $this->fetchOrder();

        $responseD = $this->request(self::POST, "/orders/{$lastOrder}/invoice", json_encode(
            [
            'status' => 'order_invoiced',
            'invoice' => [
                'key' => '99999999999999999999999999999999999999999999',
                ],
            ]
        ));
        $this->fetchOrder();

        $responseE = $this->request(self::POST, "/orders/{$lastOrder}/shipments", json_encode(
            [
                'status' => 'order_shipped',
                'shipment' => [
                    'code' => "${lastOrder}",
                    'items' => [
                        [
                            'sku' => $sku,
                            'qty' => 1,
                        ],
                    ],
                    'track' => [
                        'code' => 'BR1321830198302DR',
                        'carrier' => 'Correios',
                        'method' => 'SEDEX',
                        'url' => 'www.correios.com.br',
                    ],
                ],
            ]
        ));
        $this->fetchOrder();

        $responseF = $this->request(self::POST, "/orders/{$lastOrder}/delivery", json_encode(['status' => 'complete']));
        $this->fetchOrder();

        return [
            'description' => 'Atualizar status de pedido para entregue com código de rastreio e chave da NFE',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode()
             && 201 === $responseD->getHttpStatusCode()
             && 201 === $responseE->getHttpStatusCode()
             && 201 === $responseF->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function updateOrderStatusForCanceled()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;

        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));
        $lastOrder = $this->fetchOrder();

        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/approval", json_encode(['status' => 'payment_received']));
        $this->fetchOrder();

        $responseD = $this->request(self::POST, "/orders/{$lastOrder}/cancel", json_encode(['status' => 'order_canceled']));
        $this->fetchOrder();

        return [
            'description' => 'Atualizar status de pedido para cancelado',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode()
             && 201 === $responseD->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function updateOrderStatusForDeliveryException()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;
        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));
        $lastOrder = $this->fetchOrder();

        $responseC = $this->request(self::POST, "/orders/{$lastOrder}/approval", json_encode(['status' => 'payment_received']));
        $this->fetchOrder();

        $responseD = $this->request(self::POST, "/orders/{$lastOrder}/invoice", json_encode(
            [
            'status' => 'order_invoiced',
            'invoice' => [
                'key' => '99999999999999999999999999999999999999999999',
                ],
            ]
        ));
        $this->fetchOrder();
        $responseE = $this->request(self::POST, "/orders/{$lastOrder}/shipments", json_encode(
            [
                'status' => 'order_shipped',
                'shipment' => [
                    'code' => "${lastOrder}",
                    'items' => [
                        [
                            'sku' => $sku,
                            'qty' => 1,
                        ],
                    ],
                    'track' => [
                        'code' => 'BR1321830198302DR',
                        'carrier' => 'Correios',
                        'method' => 'SEDEX',
                        'url' => 'www.correios.com.br',
                    ],
                ],
            ]
        ));
        $this->fetchOrder();

        $responseF = $this->request(self::POST, "/orders/{$lastOrder}/shipment_exception", json_encode([
            'status' => 'shipment_exception',
            'shipment_exception' => [
                'occurrence_date' => '2019-05-21T03:53:00-03:00',
                'observation' => 'Cliente não estava presente para receber a entrega',
            ],
        ]));
        $this->fetchOrder();

        return [
            'description' => 'Atualizar status de pedido para exceção de entrega',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode()
             && 201 === $responseD->getHttpStatusCode()
             && 201 === $responseE->getHttpStatusCode()
             && 201 === $responseF->getHttpStatusCode(),
            'detail' => sprintf("Order: '%s' | Sku: '%s'", preg_replace('/[a-zA-Z\\-]/', '', $lastOrder), $sku),
        ];
    }

    protected function fetchOrderQueue()
    {
        $sku = $this->getNextSku();
        $product = $this->getNewProduct($sku);
        $product['qty'] = 100;
        $responseA = $this->request(self::POST, '/products', json_encode(['product' => $product]));

        $order = $this->getNewOrder();
        $order['items'][0]['id'] = $sku;
        $order['items'][0]['qty'] = 1;

        $responseB = $this->request(self::POST, '/orders', json_encode(['order' => $order]));
        $responseC = $this->request(self::POST, '/orders', json_encode(['order' => $order]));
        $responseD = $this->request(self::POST, '/orders', json_encode(['order' => $order]));

        $codes = [];
        do {
            try {
                $response = $this->request(self::GET, '/queues/orders');
                $code = $response->getData()['code'];
                $this->request(self::DELETE, "/queues/orders/${code}");
            } catch (\Exception $e) {
            }
            $codes[] = preg_replace('/[a-zA-Z\\-]/', '', $code);
        } while (!empty($response->getResponseRaw()));

        $codes = array_filter($codes);

        return [
            // Importante realizar um GET para consumir o pedido e um DELETE para retira-lo da fila.
            'description' => 'Consumir todos os pedidos pela fila de integração (/queues).',
            'status' => 201 === $responseA->getHttpStatusCode()
             && 201 === $responseB->getHttpStatusCode()
             && 201 === $responseC->getHttpStatusCode()
             && 201 === $responseD->getHttpStatusCode(),
            'detail' => sprintf("Orders: '%s' | Sku: '%s'", implode(',', $codes), $sku),
        ];
    }
}
