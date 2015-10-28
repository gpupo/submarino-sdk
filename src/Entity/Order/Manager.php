<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\SubmarinoSdk\Entity\ManagerAbstract;

class Manager extends ManagerAbstract
{
    protected $entity = 'Order';

    protected $maps = [
        'confirm'       => ['POST', '/order/{itemId}/confirm'],
        'saveStatus'    => ['PUT', '/order/{itemId}/status'],
        'findById'      => ['GET', '/order/{itemId}'],
        'fetch'         => ['GET', '/order?offset={offset}&limit={limit}&purchaseDate={purchaseDate}&store={store}&siteId={siteId}&status={status}'],
    ];

    /**
     * @param int   $offset
     * @param int   $limit
     * @param array $parameters
     *
     * @return \Gpupo\CommonSdk\Entity\CollectionInterface
     */
    public function fetch($offset = 1, $limit = 50, array $parameters = [])
    {
        return parent::fetch($offset, $limit, array_merge([
            'status'        => null,
            'purchaseDate'  => null,
            'store'         => null,
            'siteId'        => null,
        ], $parameters));
    }

    /**
     * Obtém a lista de pedidos recém aprovados e que esperam processamento.
     */
    public function fetchQueue($offset = 0, $limit = 50, array $parameters = [])
    {
        return $this->fetch($offset, $limit, array_merge(['status' => 'APPROVED'], $parameters));
    }

    public function saveStatus(Order $order)
    {
        return $this->execute($this->factoryMap('saveStatus',
            ['itemId' => $order->getId()]), $order->getStatus()->toJson());
    }

    /**
     * Confirmação de recebimento de pedido.
     *
     * Esta função faz uma chamada para o endpoint POST /order/{itemId}/confirm
     *
     * Campos e parâmetros:
     *  - code: Indica se o parceiro aprova (0) ou não (1) o pedido
     *  - message: mensagem explicando o status da resposta.
     *
     * Exemplo:
     *
     *  {"code": "0", "message": "Success"}
     *  {"code": "1", "message": "Failure"}
     *
     * @link https://api-sandbox.bonmarketplace.com.br/docs/confirmacaoPedido.shtml
     *
     * @param integer $itemId
     * @param boolean $sucessfully
     * @param string  $message
     *
     * @return boolean
     */
    public function confirm($itemId, $successfully, $message)
    {
        try {
            $this->execute(
                $this->factoryMap(
                    'confirm',
                    ['itemId' => $itemId]
                ),
                sprintf(
                    '{"code": "%d", "message": "%s"}',
                    $successfully ? 0 : 1,
                    $message
                )
            );

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
