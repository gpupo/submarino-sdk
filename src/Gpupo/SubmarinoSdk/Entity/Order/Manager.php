<?php

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $entity = 'Order';

    protected $maps = [
        'saveStatus'    => ['PUT', '/order/{itemId}/status'],
        'findById'      => ['GET', '/order/{itemId}'],
        'fetch'         => ['GET', '/order?offset={offset}&limit={limit}&purchaseDate={purchaseDate}&store={store}&siteId={siteId}'],
    ];

    /**
     *
     * @param  int                                         $offset
     * @param  int                                         $limit
     * @param  array                                       $parameters
     * @return \Gpupo\CommonSdk\Entity\CollectionInterface
     */
    public function fetch($offset = 1, $limit = 50, array $parameters = [])
    {
        return parent::fetch($offset, $limit, array_merge([
            'purchaseDate'  => null,
            'store'         => null,
            'siteId'        => null,
        ],$parameters));
    }

    public function saveStatus(Order $order)
    {
        return $this->execute($this->factoryMap('saveStatus',
            ['itemId' => $order->getId()]), $order->toSaveStatus());
    }

}
