<?php

/*
 * This file is part of submarino-sdk
 *
 * (c) Gilmar Pupo <g@g1mr.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\ClientAbstract;
use Gpupo\CommonSdk\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    public function getDefaultOptions()
    {
        return [
            'token'         => false,
            'base_url'      => 'https://api-{VERSION}.bonmarketplace.com.br',
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
            'Authorization: Basic '.base64_encode($token.':'),
            'Content-Type: application/json;charset=UTF-8',
        ));

        return $transport;
    }
}
