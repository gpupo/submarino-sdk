<?php

/*
 * This file is part of gpupo/submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Status;

class Invoiced extends \Gpupo\SubmarinoSdk\Entity\Order\Invoiced
{
    public function getSchema()
    {
        return array_merge(
            parent::getSchema(),
            [
                'danfeXml'  => 'string',
            ]
        );
    }

    public function setRequired()
    {
        return $this->setRequiredSchema([
            'number',
            'line',
            'issueDate',
            'key',
        ]);
    }
}
