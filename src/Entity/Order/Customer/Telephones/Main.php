<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Customer\Telephones;

class Main extends Telephones
{
    public function getSchema()
    {
        return  [
            'number'    => 'string',
            'ddd'       => 'string',
        ];
    }
}
