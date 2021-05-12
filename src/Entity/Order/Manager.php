<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\Common\Entity\CollectionInterface;
use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping\Product\Collection as ProductCollection;
use Gpupo\CommonSchema\ArrayCollection\Trading\Order\Shipping\Transport\Transport;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Gpupo\SubmarinoSdk\Entity\AbstractManager;
use Gpupo\SubmarinoSdk\Entity\Order\Transport\Plp;

class Manager extends AbstractManager
{
    const JURISDICTION = 'order';

    const JSON_DATA_KEY = 'orders';

    protected $entity = Order::class;

    protected $maps = [
        'findById' => ['GET', '/orders/{itemId}'],
        'fetch' => ['GET', '/queues/orders'],
        'delete' => ['DELETE', '/queues/orders/{itemId}'],
        'invoice' => ['POST', '/orders/{itemId}/invoice'],
        'shipments' => ['POST', '/orders/{itemId}/shipments'],
        'delivery' => ['POST', '/orders/{itemId}/delivery'],
        'factoryPlp' => ['POST', '/shipments/b2w/'],
        'requestPlp' => ['GET', '/shipments/b2w/view?plp_id={plpId}'],
        'fetchWithFilters' => ['GET', '/orders?{filtersHttpBuildedQuery}'],
    ];
    //
    // public function findById($itemId): ?CollectionInterface
    // {
    //     $item = parent::findById($itemId);
    //
    //     if ($item) {
    //         return $this->factoryEntity($item);
    //     }
    // }

    /**
     * Obtém a lista de pedidos recém aprovados e que esperam processamento.
     */
    public function fetchQueue(int $offset = 0, int $limit = 50, array $parameters = []): ?TranslatorDataCollection
    {
        $result = $this->fetch($offset, $limit, array_merge(['status' => 'APPROVED'], $parameters));

        if ($result->first()->isEmpty() || !$result->first()->get('channel')) {
            return null;
        }

        $translator = new Translator(['native' => $result->first()]);
        $trading = $translator->export();

        return $trading;
    }

    /**
     * Obtém a lista de pedidos a partir das regras dos filtros.
     */
    public function fetchWithFilters(int $offset = 0, int $limit = 50, array $filterList = []): ?TranslatorDataCollection
    {
        $parameters = [
            'filtersHttpBuildedQuery' => http_build_query([
                'filters' => $filterList,
            ]),
        ];

        $rawResult = $this->rawFetch($offset, $limit, $parameters, 'fetchWithFilters');

        if (1 > (int)$rawResult->get('total')) {
            return null;
        }
        
        $collection = new TranslatorDataCollection();

        foreach($rawResult['orders'] as $rawOrder) {
            $order = new Order($rawOrder);
            $translator = new Translator(['native' => $order]);
            $collection->add($translator->export());
        }
        
        return $collection;
    }

    public function delete($itemId)
    {
        return $this->execute($this->factoryMap('delete', ['itemId' => $itemId]));
    }

    public function notifyInvoiced($itemId, $invoiceKey, $moveStatus = true)
    {
        $body = [
            'invoice' => [
                'key' => $invoiceKey,
            ],
        ];

        if ($moveStatus) {
            $body['status'] = 'order_invoiced';
        }

        return $this->execute($this->factoryMap('invoice', ['itemId' => $itemId]), json_encode($body));
    }

    public function addShipment(string $itemId, ProductCollection $productCollection, Transport $transport, $moveStatus = false)
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
            'shipment' => [
                'delivered_carrier_date' => $transport->get('date_ship') ? $transport->get('date_ship')->format('c') : null,
                'code' => $itemId,
                'items' => $items,
                'track' => $track,
            ],
        ];

        if ($moveStatus) {
            $body['status'] = 'order_shipped';
        }

        return $this->execute($this->factoryMap('shipments', ['itemId' => $itemId]), json_encode($body));
    }

    public function notifyShipped(string $itemId)
    {
        $body = ['status' => 'order_shipped'];

        return $this->execute($this->factoryMap('shipments', ['itemId' => $itemId]), json_encode($body));
    }

    public function notifyDelivered(string $itemId)
    {
        $body = ['status' => 'complete'];

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

    public function factoryPlp($itemId, $fill = false): ?Plp
    {
        $body = [
            'order_remote_codes' => [
                (string) $itemId,
            ],
        ];

        $response = $this->execute($this->factoryMap('factoryPlp', []), json_encode($body));
        $data = $response->getData();

        if (200 <= $response->getHttpStatusCode() && 300 > $response->getHttpStatusCode()) {
            $re = '/Plp (\d+) agrupada com sucesso./m';
            preg_match($re, $data->get('message'), $matches);

            $plp = new Plp([
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

    public function downloadPlp(Plp $plp, $tmpDirectory = '/tmp'): string
    {
        $filename = sprintf('%s/submarino_sdk_plp-%s.pdf', $tmpDirectory, $plp->getId());
        $map = $this->factoryMap('requestPlp', ['plpId' => $plp->getId()]);
        $request = $this->factoryRequestByMap($map);
        $headers = $request->getHeaders();
        $headers['Accept'] = 'application/pdf';
        $request->set('header', $headers);

        return $this->downloadFileByRequest($request, $filename);
    }

    //
    // protected function fetchPrepare($data)
    // {
    //     return parent::fetchPrepare([$data]);
    // }

    // protected function factoryEntity($data): CollectionInterface
    // {
    //     return $data;
    // }
}
