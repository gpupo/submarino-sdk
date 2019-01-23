<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk
 * Created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file
 * LICENSE which is distributed with this source code.
 * Para a informação dos direitos autorais e de licença você deve ler o arquivo
 * LICENSE que é distribuído com este código-fonte.
 * Para obtener la información de los derechos de autor y la licencia debe leer
 * el archivo LICENSE que se distribuye con el código fuente.
 * For more information, see <https://opensource.gpupo.com/>.
 *
 */

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\Client\ClientAbstract;
use Gpupo\CommonSdk\Client\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    protected $endpoint_domain = 'api.skyhub.com.br';

    protected function renderAuthorization(): array
    {
        return [
            'X-User-Email' => $this->getOptions()->get('user_email'),
            'X-Api-Key' => $this->getOptions()->get('api_key'),
            'X-Accountmanager-Key' => $this->getOptions()->get('accountmanager_key'),
        ];
    }
}
