<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\PaymentMethods;

use Gpupo\CommonSdk\Entity\CollectionAbstract;

class PaymentMethods extends CollectionAbstract
{
    public function factoryElement($data)
    {
        return new PaymentMethod($data);
    }
}
