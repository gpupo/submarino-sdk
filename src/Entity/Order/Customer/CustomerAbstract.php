<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gpupo\SubmarinoSdk\Entity\Order\Customer;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

abstract class CustomerAbstract extends EntityAbstract implements EntityInterface
{
    public function factorySchema(array $objects)
    {
        $list = [];

        foreach (array_merge(['pf', 'pj', 'telephones'], $objects) as $item) {
            $list[$item] = 'object';
        }

        return $list;
    }
}
