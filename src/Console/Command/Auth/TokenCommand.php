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

namespace Gpupo\SubmarinoSdk\Console\Command\Auth;

use Gpupo\SubmarinoSdk\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TokenCommand extends AbstractCommand
{
    public function requestNewAccessToken()
    {
        return $this->getFactory()->getClient()->requestToken();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::prefix.'auth:token')
            ->setDescription('Get new access token');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $data = $this->requestNewAccessToken();

            return $this->saveCredentials($data->toArray(), $output);
        } catch (\Exception $exception) {
            $output->writeln(sprintf('Error: <bg=red>%s</>', $exception->getmessage()));
        }
    }
}
