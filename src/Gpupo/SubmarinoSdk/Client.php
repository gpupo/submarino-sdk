<?php

namespace Gpupo\SubmarinoSdk;

use Doctrine\Common\Collections\ArrayCollection;

class Client
{
    const VERSION = 1;

    protected $options = [];

    protected static $instance;

    /**
     * Permite acesso a instancia dinamica
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $class=get_called_class();
            self::$instance = new $class();
        }

        return self::$instance;
    }

    public function factoryRequest($resource, $post = false)
    {
        $curlClient = curl_init();
        curl_setopt($curlClient, CURLOPT_SSLVERSION, 3);
        curl_setopt( $curlClient , CURLOPT_POST, $post);
        curl_setopt( $curlClient , CURLOPT_RETURNTRANSFER, true );
        curl_setopt($curlClient, CURLOPT_VERBOSE, $this->getOptions()->get('verbose'));

        $url = $this->getOptions()->get('base_url') . '/'
            . $this->getOptions()->get('version') . $resource;

        curl_setopt($curlClient, CURLOPT_URL, $url);

        $token = $this->getOptions()->get('token');

        if (empty($token)) {
            throw new \InvalidArgumentException('Token nao informado');
        }

        curl_setopt($curlClient, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($token . ':'),
            'Content-Type: application/json',
        ));

        return $curlClient;
    }

    public function getOptions()
    {
        return $this->options;
    }

    protected function setOptions(Array $options = [])
    {
        $defaultOptions = [
            'token'     => false,
            'base_url'  => 'https://api-marketplace.submarino.com.br',
            'version'   => 'sandbox',
            'verbose'   => false, // Display communication with server
        ];

        $list = array_merge($defaultOptions, $options);

        $this->options = new ArrayCollection($list);

        return $this;
    }

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    protected function exec($request)
    {
        $data = [];
        $data['responseRaw'] = curl_exec($request);
        $data['httpStatusCode'] = curl_getinfo($request, CURLINFO_HTTP_CODE);
        curl_close($request);

        return new Response($data);
    }

    public function get($resource)
    {
        $request = $this->factoryRequest($resource);

        return $this->exec($request);
    }

    public function post($resource, $body)
    {
        $request = $this->factoryRequest($resource, true);

        curl_setopt($request, CURLOPT_POSTFIELDS, $body);

        return $this->exec($request);
    }

    public function put($resource, $body)
    {
        $request = $this->factoryRequest($resource, true);

        curl_setopt($request, CURLOPT_PUT, true);

        $pointer = fopen('php://temp/maxmemory:512000', 'w+');
        //$pointer = tmpfile();
        if (!$pointer) {
            throw new \Exception('could not open temp memory data');
        }
        fwrite($pointer, $body);
        fseek($pointer, 0);

        curl_setopt($request, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($request, CURLOPT_INFILE, $pointer);
        curl_setopt($request, CURLOPT_INFILESIZE, strlen($body));

        //curl_setopt($request, CURLOPT_POSTFIELDS, $body);
        //curl_setopt($request, CURLOPT_CUSTOMREQUEST, "PUT");
        return $this->exec($request);
    }
}
