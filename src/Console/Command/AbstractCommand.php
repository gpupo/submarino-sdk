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

namespace Gpupo\SubmarinoSdk\Console\Command;

use Gpupo\CommonSdk\Entity\CollectionContainerInterface;
use Gpupo\CommonSdk\Entity\EntityInterface;
use Gpupo\SubmarinoSdk\Factory;
use Gpupo\Common\Console\Command\AbstractCommand as Core;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractCommand extends Core
{
    const prefix = 'markethub:submarino:';

    protected $factory;

    public function __construct(Factory $factory = null)
    {
        $this->factory = $factory;

        parent::__construct();
    }

    public function getProjectDataFilename(): string
    {
        return $this->getFactory()->getOptions()->get('extra_file') ?: 'var/parameters.yaml';
    }

    public function getFactory(): Factory
    {
        if (!$this->factory instanceof Factory) {
            throw new \InvalidArgumentException('Factory must be defined!');
        }

        return $this->factory;
    }

    protected function addOptionsForList()
    {
        $this
            ->addOption(
                'offset',
                null,
                InputOption::VALUE_REQUIRED,
                'Current offset of list',
                0
            )
            ->addOption(
                'max',
                null,
                InputOption::VALUE_REQUIRED,
                'Max items quantity',
                100
            );
    }
}
