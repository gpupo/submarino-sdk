<?php

namespace Gpupo\SubmarinoSdk;

use Gpupo\SubmarinoSdk\Entity\Collection;

class Response extends Collection
{
    public function getData()
    {
        return new Collection(json_decode($this->get('responseRaw'), true));
    }
}
