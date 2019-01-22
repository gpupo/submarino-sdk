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
use Gpupo\SubmarinoSdk\Entity\AbstractManager;

class Manager extends AbstractManager
{
    protected $entity = 'Order';

    protected $maps = [
        'confirm' => ['POST', '/order/{itemId}/confirm'],
        'saveStatus' => ['PUT', '/order/{itemId}/status'],
        'findById' => ['GET', '/order/{itemId}'],
        'fetch' => ['GET', '/order?offset={offset}&limit={limit}&purchaseDate={purchaseDate}&store={store}&siteId={siteId}&status={status}'],
    ];

    /**
     * @param int   $offset
     * @param int   $limit
     * @param array $parameters
     * @param mixed $route
     *
     * @return \Gpupo\CommonSdk\Entity\CollectionInterface
     */
    public function fetch($offset = 1, $limit = 50, array $parameters = [], $route = 'fetch'): ?CollectionInterface
    {
        return parent::fetch($offset, $limit, array_merge([
            'status' => null,
            'purchaseDate' => null,
            'store' => null,
            'siteId' => null,
        ], $parameters));
    }

    /**
     * Obtém a lista de pedidos recém aprovados e que esperam processamento.
     *
     * @param mixed $offset
     * @param mixed $limit
     */
    public function fetchQueue($offset = 0, $limit = 50, array $parameters = [])
    {
        return $this->fetch($offset, $limit, array_merge(['status' => 'APPROVED'], $parameters));
    }

    public function saveStatus(Order $order)
    {
        return $this->execute($this->factoryMap(
            'saveStatus',
            ['itemId' => $order->getId()]
        ), $order->getStatus()->toJson());
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
     * @see https://api-sandbox.bonmarketplace.com.br/docs/confirmacaoPedido.shtml
     *
     * @param int    $itemId
     * @param bool   $successfully
     * @param string $message
     *
     * @return bool
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
