<?php

namespace Frne\Bundle\Neo4jUserBundle\Check;

use ZendDiagnostics\Check\AbstractCheck;
use ZendDiagnostics\Result\Failure;
use ZendDiagnostics\Result\Success;

class Neo4j extends AbstractCheck
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $host
     * @param int $port
     */
    public function __construct(
        $host = 'localhost',
        $port = 7474
    ) {
        $this->host = $host;
        $this->port = $port;
    }

    public function check()
    {
        $url = "http://$this->host:$this->port/db/data";
        $result = new Success("Neo4j avaiable on $url");
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
            )
        );

        if (curl_exec($curl) === false) {
            $result = new Failure(curl_error($curl), array('url' => $url));
        }
        curl_close($curl);

        return $result;
    }
}
