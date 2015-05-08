<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Payer;

use Gpupo\SubmarinoSdk\Entity\Order\Customer\CustomerAbstract;

class Payer extends CustomerAbstract
{
    public function getSchema()
    {
        return $this->factorySchema(['billingAddress']);
    }
}
