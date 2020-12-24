<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Entity\Order;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Order extends EntityAbstract implements EntityInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function getSchema(): array
    {
        return [
            'code' => 'string',
            'channel' => 'string',
            'shipping_method' => 'string',
            'shipping_cost' => 'number',
            'total_ordered' => 'number',
            'shipping_cost' => 'number',
            'shipping_address' => 'array',
            'billing_address' => 'array',
            'customer' => 'array',
            'items' => 'array',
            'status' => 'array',
            'placed_at' => 'string',
            'updated_at' => 'string',
            'import_info' => 'array',
            'payments' => 'array',
            'shipments' => 'array',
        ];
    }
}
