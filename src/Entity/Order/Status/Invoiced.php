<?php

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