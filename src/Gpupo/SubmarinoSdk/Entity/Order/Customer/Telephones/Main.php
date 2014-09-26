<?php

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
