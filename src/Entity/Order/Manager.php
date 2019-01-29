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
use Gpupo\CommonSchema\ORM\Entity\Trading\Order\Order;
use Gpupo\SubmarinoSdk\Entity\AbstractManager;

class Manager extends AbstractManager
{
    const JURISDICTION = 'order';

    protected $entity = Order::class;

    protected $maps = [
        'saveStatus' => ['PUT', '/orders/{itemId}/status'],
        'findById' => ['GET', '/orders/{itemId}'],
        'fetch' => ['GET', '/orders?page={page}&per_page={limit}&limit={limit}&filters[sale_systems][]={siteId}&filters[statuses][]={status}'],
    ];

    /**
     * Obtém a lista de pedidos recém aprovados e que esperam processamento.
     */
    public function fetchQueue(int $offset = 0, int $limit = 50, array $parameters = []): ?CollectionInterface
    {
        return $this->fetch($offset, $limit, array_merge(['status' => 'APPROVED'], $parameters));
    }
}
