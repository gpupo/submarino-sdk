<?php

declare(strict_types=1);

/*
 * This file is part of gpupo/submarino-sdk created by Gilmar Pupo <contact@gpupo.com>
 * For the information of copyright and license you should read the file LICENSE which is
 * distributed with this source code. For more information, see <https://opensource.gpupo.com/>
 */

namespace Gpupo\SubmarinoSdk\Entity\Order\Transport;

use Gpupo\CommonSdk\Entity\EntityAbstract;
use Gpupo\CommonSdk\Entity\EntityInterface;

class Document extends EntityAbstract implements EntityInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function getSchema(): array
    {
        return [
            'codCliente' => 'string',
            'docExterno' => 'string',
            'dtPrometida' => 'string',
            'tpEntrega' => 'string',
            'pesoTotal' => 'string',
            'marca' => 'string',
            'qtVolumes' => 'string',
            'numeroContratoTransp' => 'string',
            'nomeEmbarcador' => 'string',
            'telefoneEmbarcador' => 'string',
            'emailEmbarcador' => 'string',
            'tpServico' => 'string',
            'numNotaFiscal' => 'string',
            'serieNotaFiscal' => 'string',
            'megaRota' => 'string',
            'rota' => 'string',
            'telefoneContato' => 'string',
            'vlEntrega' => 'string',
            'cartaoPostagem' => 'string',
            'servicoAdicional' => 'string',
            'destinatario' => 'array',
            'remetente' => 'array',
            'awbs' => 'object',
        ];
    }

    public function getTrackingCodes()
    {
        $data = [];

        foreach ($this->getAwbs() as $awb) {
            $data[] = $awb->getCodigoAwb();
        }

        return $data;
    }
}
