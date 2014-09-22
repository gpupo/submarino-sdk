<?php

namespace Gpupo\Tests;

use Gpupo\SubmarinoSdk\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{
    protected function getResourceContent($file)
    {
        return file_get_contents($this->getResourceFilePath($file));
    }

    protected function getResourceFilePath($file)
    {
        $path = 'Resources/' . $file;
        if (file_exists($path)) {
            return $path;
        } else {
            throw new \InvalidArgumentException('File Not Exist');
        }
    }

    public function factoryClient()
    {
        $log = new Logger('SubmarinoSdk');
        $log->pushHandler(new StreamHandler(
            $this->getResourceFilePath('logs/tests.log'), Logger::DEBUG));

        return Client::getInstance()
            ->setOptions(['token' => API_TOKEN, 'verbose' => VERBOSE])
            ->setLogger($log);
    }

    public function dataProviderProducts()
    {
        return include($this->getResourceFilePath('fixture/Products.php'));
    }
}
