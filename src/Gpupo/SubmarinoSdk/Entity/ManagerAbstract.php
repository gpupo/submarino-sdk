<?php

namespace Gpupo\SubmarinoSdk\Entity;

use Gpupo\SubmarinoSdk\Client;

abstract class ManagerAbstract
{
    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    
    public function getClient()
    {
        
    }
}