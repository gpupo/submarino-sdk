<?php

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\ClientAbstract;
use Gpupo\CommonSdk\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    public function getDefaultOptions()
    {
        return [
            'token'     => false,
            'base_url'  => 'https://api-marketplace.submarino.com.br',
            'version'   => 'sandbox',
            'verbose'   => false, // Display communication with server
        ];
    }
        
        

}
