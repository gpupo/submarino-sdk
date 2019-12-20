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

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSchema\AbstractTranslator;
use Gpupo\CommonSchema\TranslatorDataCollection;
use Gpupo\CommonSchema\TranslatorException;
use Gpupo\CommonSchema\TranslatorInterface;
use Gpupo\CommonSdk\Traits\LoadTrait;

class Translator extends AbstractTranslator implements TranslatorInterface
{
    use LoadTrait;

    /**
     * {@inheritdoc}
     */
    public function import(): Product
    {
        if (!$this->getForeign() instanceof TranslatorDataCollection) {
            throw new TranslatorException('Foreign missed!');
        }
        $map = $this->loadMap('foreign');

        return new Product($map);
    }

    /**
     * {@inheritdoc}
     */
    public function export(): TranslatorDataCollection
    {
        $native = $this->getNative();

        if (!is_object($native)) {
            throw new TranslatorException('$native must be a Object!');
        }
        if (!$native instanceof Product) {
            dump(get_class($native));
            throw new TranslatorException(sprintf('$native must be %s. [%s] received', Product::class, get_class($native)));
        }

        return $this->factoryOutputCollection($this->loadMap('native'));
    }

    private function loadMap($name): array
    {
        $file = __DIR__.'/map/translate.'.$name.'.map.php';
        $method = 'get'.ucfirst($name);
        $pars = [$name => $this->{$method}()];

        return $this->loadArrayFromFile($file, $pars);
    }
}
