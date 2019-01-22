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

namespace Gpupo\SubmarinoSdk\Entity;

use Gpupo\CommonSdk\Map;

class GenericManager extends AbstractManager
{
    public function factorySimpleMap(array $route, array $parameters = null)
    {
        $pars = array_merge($this->fetchDefaultParameters(), (array) $parameters);
        $map = new Map($route, $pars);

        return $map;
    }

    public function getFromRoute(array $route, array $parameters = null, $body = null)
    {
        $map = $this->factorySimpleMap($route, $parameters);
        $perform = $this->perform($map, $body);

        return $perform->getData();
    }
}
