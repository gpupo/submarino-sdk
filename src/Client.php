<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk;

use Gpupo\CommonSdk\Client\ClientAbstract;
use Gpupo\CommonSdk\Client\ClientInterface;

class Client extends ClientAbstract implements ClientInterface
{
    const ENDPOINT = 'api.skyhub.com.br';

    protected function renderAuthorization(): array
    {
        return [
            'X-User-Email' => $this->getOptions()->get('user_email'),
            'X-Api-Key' => $this->getOptions()->get('api_key'),
            'X-Accountmanager-Key' => $this->getOptions()->get('accountmanager_key'),
        ];
    }
}
