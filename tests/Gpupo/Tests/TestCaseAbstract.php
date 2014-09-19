<?php

namespace Gpupo\Tests;

use Gpupo\SubmarinoSdk\Client;

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
        return new Client(['token' => API_TOKEN, 'verbose' => VERBOSE]);
    }
    
    public function dataProviderProducts()
    {
        return include($this->getResourceFilePath('fixture/Products.php'));
    }
}
