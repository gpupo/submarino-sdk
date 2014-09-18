<?php

namespace Gpupo\Tests;

abstract class TestCaseAbstract extends \PHPUnit_Framework_TestCase
{
    protected function getResourceContent($file)
    {
        $path = 'Resources/' . $file;
        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            throw new \InvalidArgumentException('File Not Exist');
        }
    }
}
