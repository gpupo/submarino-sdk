<?php

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://www.gpupo.com/>.
 */

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\Client\ClientAbstract;
use Gpupo\CommonSdk\Client\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    /**
     * @internal
     */
    public function getDefaultOptions()
    {
        return [
            'token'      => false,
            'base_url'   => 'https://api-{VERSION}.bonmarketplace.com.br',
            'version'    => 'sandbox',
            'verbose'    => false,
            'sslVersion' => 'SecureTransport',
            'cacheTTL'   => 3600,
        ];
    }

    protected function renderAuthorization()
    {
        $token = $this->getOptions()->get('token');

        if (empty($token)) {
            throw new \InvalidArgumentException('Token nao informado');
        }

        return 'Authorization: Basic '.base64_encode($token.':');
    }
}
