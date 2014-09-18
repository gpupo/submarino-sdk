<?php
/**
 * Gerenciamento de entidades
 */
namespace Gpupo\SubmarinoSdk\Entity;

class EntityManager
{
    /**
     * Acesso a um novo objeto da Entidade
     *
     * @param string $string     Nome da Entidade
     * @param string $objectName Nome do Objeto
     *
     * @return object
     */
    public static function factory($string, $objectName, array $data = null)
    {
        $object = __NAMESPACE__ . '\\' . ucfirst($string) . '\\' . $objectName;

        return new $object($data);
    }

}
