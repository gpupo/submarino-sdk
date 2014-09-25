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

    protected function getResourceJson($file)
    {
        return json_decode($this->getResourceContent($file), true);

    }
    protected function getResourceFilePath($file)
    {
        $path = 'Resources/' . $file;
        if (file_exists($path)) {
            return $path;
        } else {
            throw new \InvalidArgumentException('File ' . $path . ' Not Exist');
        }
    }

    public function getLogger()
    {
        $channel = str_replace('\\', '.', get_called_class());
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler(
            $this->getResourceFilePath('logs/tests.log'), Logger::DEBUG));

        return $log;
    }

    public function factoryClient()
    {
        $client = Client::getInstance()
            ->setOptions(['token' => API_TOKEN, 'verbose' => VERBOSE]);

        $client->setLogger($this->getLogger());

        return $client;
    }

    public function dataProviderProducts()
    {
        //return include($this->getResourceFilePath('fixture/Products.php'));
        return $this->getResourceJson('fixture/Products.json');
    }

    public function dataProviderSkus()
    {
        return $this->getResourceJson('fixture/Skus.json');
    }
}
