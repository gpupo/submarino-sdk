<?php

namespace Gpupo\SubmarinoSdk\Entity\Product;

use Gpupo\CommonSdk\Entity\FactoryAbstract;

class Factory extends FactoryAbstract
{
    public static function factory($objectName, $data = null)
    {
        if (!is_array($data)) {
            $data = [];
        }

        $object = __NAMESPACE__ . '\\' . $objectName;

        return new $object($data);
    }

    public static function __callStatic($method, $args)
    {
        $command = substr($method, 0, 7);
        $objectName = substr($method, 7);
        $objectName[0] = strtolower($objectName[0]);

        if ($command == "factory") {
            return self::factory($objectName, current($args), next($args));
        } else {
            throw new \BadMethodCallException("There is no method ".$method);
        }
    }

}
