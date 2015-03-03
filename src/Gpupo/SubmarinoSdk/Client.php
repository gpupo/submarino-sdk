<?php

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\ClientAbstract;
use Gpupo\CommonSdk\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    public function getDefaultOptions()
    {
        return [
            'token'         => false,
            'base_url'      => 'https://api-marketplace.submarino.com.br',
            'version'       => 'sandbox',
            'verbose'       => false,
            'sslVersion'    => 'SecureTransport',
            'cacheTTL'      => 3600,
        ];
    }

    protected function factoryTransport()
    {
        $transport = parent::factoryTransport();

        $token = $this->getOptions()->get('token');

        if (empty($token)) {
            throw new \InvalidArgumentException('Token nao informado');
        }

        $transport->setOption(CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($token . ':'),
            'Content-Type: application/json;charset=UTF-8',
        ));

        return $transport;
    }

    public function getResourceUri($resource)
    {
        return $this->getOptions()->get('base_url') . '/'
            . $this->getOptions()->get('version') . $resource;
    }
}
