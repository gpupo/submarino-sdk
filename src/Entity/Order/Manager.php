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

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping\Product\Collection as ProductCollection;
use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping\Transport\Transport;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Gpupo\SubmarinoSdk\Entity\AbstractManager;
use Gpupo\SubmarinoSdk\Entity\Order\Transport\Plp;
use Gpupo\SubmarinoSdk\Translator\OrderTranslator;
use Psr\Http\Message\ResponseInterface;

class Manager extends AbstractManager
{
    const JURISDICTION = 'order';

    protected $entity = Order::class;

    protected $maps = [
        'saveStatus' => ['PUT', '/orders/{itemId}/status'],
        'findById' => ['GET', '/orders/{itemId}'],
        'fetch' => ['GET', '/queues/orders'],
        'delete' => ['DELETE', '/queues/orders/{itemId}'],
        'invoice' => ['POST', '/orders/{itemId}/invoice'],
        'shipments' => ['POST', '/orders/{itemId}/shipments'],
        'delivery' => ['POST', '/orders/{itemId}/delivery'],
        'factoryPlp' => ['POST', '/shipments/b2w/'],
        'requestPlp' => ['GET', '/shipments/b2w/view?plp_id={plpId}'],
    ];

    /**
     * Obtém a lista de pedidos recém aprovados e que esperam processamento.
     */
    public function fetchQueue(int $offset = 0, int $limit = 50, array $parameters = []): ?CollectionInterface
    {
        $result = $this->fetch($offset, $limit, array_merge(['status' => 'APPROVED'], $parameters));

        if ($result->first()->isEmpty()) {
            return null;
        }

        $translator = new OrderTranslator();
        $translator->setForeign(new TranslatorDataCollection($result->first()->toArray()));
        $trading = $translator->import();
        // $trading = $this->factoryORM($trading, 'Entity\Trading\Trading');

        return $trading;
    }

    public function delete($itemId)
    {
        return $this->execute($this->factoryMap('delete', ['itemId' => $itemId]));
    }

    public function notifyInvoiced($itemId, $invoiceKey)
    {
        $body = [
            'status' => 'order_invoiced',
            'invoice' => [
                'key' => $invoiceKey,
            ],
        ];

        return $this->execute($this->factoryMap('invoice', ['itemId' => $itemId]), json_encode($body));
    }

    public function notifyShipped(string $itemId, string $shippingCode, ProductCollection $productCollection, Transport $transport)
    {
        $items = [];
        foreach ($productCollection as $product) {
            $items[] = [
                'sku' => $product->get('seller_product_id'),
                'qty' => $product->get('quantity'),
            ];
        }

        $track = [
            'code' => $transport->get('tracking_number'),
            'carrier' => $transport->get('carrier'),
            'method' => $transport->get('delivery_service'),
            'url' => $transport->get('link'),
        ];

        $body = [
            'status' => 'order_shipped',
            'shipment' => [
                'code' => $shippingCode,
                'items' => $items,
                'track' => $track,
            ],
        ];

        return $this->execute($this->factoryMap('shipments', ['itemId' => $itemId]), json_encode($body));
    }

    public function notifyDelivered($itemId)
    {
        $body = [
            'status' => 'complete',
        ];

        return $this->execute($this->factoryMap('delivery', ['itemId' => $itemId]), json_encode($body));
    }

    public function fetchPlp($plpId)
    {
        $response = $this->execute($this->factoryMap('requestPlp', ['plpId' => $plpId]));

        $data = $response->getData()->get('plp');
        $data['documents'] = $response->getData()->get('docsExternos');
        $plp = new Plp($data);

        return $plp;
    }

    public function factoryPlp($itemId, $fill = false):? Plp
    {
        $body = [
            'order_remote_codes' => [
                $itemId,
            ],
        ];

        $response = $this->execute($this->factoryMap('factoryPlp',[], json_encode($body)));
        $data = $response->getData();

        if (200 === $response->getHttpStatusCode()) {
            $re = '/Plp (\d+) agrupada com sucesso./m';
            preg_match($re, $data->get('message'), $matches);

            $plp =  new Plp([
                'id' => (int) $matches[1],
            ]);
        }

        if (true === $fill) {
            return $this->fillPlp($plp);
        }

        return $plp;
    }

    public function fillPlp(Plp $plp): Plp
    {
        return $this->fetchPlp($plp->getId());
    }

    public function downloadPlp(Plp $plp, $tempDirectory = '/tmp'): string
    {
        $filename = sprintf('%s/submarino_sdk_plp-%s.pdf', $tempDirectory, $plp->getId());
        $map = $this->factoryMap('requestPlp', ['plpId' => $plp->getId()]);
        $request = $this->factoryRequestByMap($map);
        $headers = $request->getHeaders();
        $headers['Accept'] = "application/pdf";
        $request->set('headers', $headers);

        return $this->downloadFileByRequest($request, $filename);
    }

    protected function fetchPrepare($data)
    {
        return parent::fetchPrepare([$data]);
    }

    protected function factoryEntity($data): CollectionInterface
    {
        return $data;
    }
}
