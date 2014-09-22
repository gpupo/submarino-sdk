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
    
    public function factoryRequest($resource, $post = false)
    {
        $curlClient = parent::factoryRequest($resource, $post);
        
        $token = $this->getOptions()->get('token');

        if (empty($token)) {
            throw new \InvalidArgumentException('Token nao informado');
        }

        curl_setopt($curlClient, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($token . ':'),
            'Content-Type: application/json',
        ));
        
        return $curlClient;
    }
    
    public function getResourceUri($resource)
    {
        return $this->getOptions()->get('base_url') . '/'
            . $this->getOptions()->get('version') . $resource;
    }
}
