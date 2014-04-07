<?php


namespace Frne\Bundle\Neo4jUserBundle\Check;


class Neo4jTest extends \PHPUnit_Framework_TestCase
{

    public function testImplementsAbstractCheck()
    {
        $check = new Neo4j();
        $this->assertInstanceOf('ZendDiagnostics\Check\AbstractCheck', $check);
    }

    public function testCheckFailsIfNoNeo4jServerIsRunning()
    {
        $check = new Neo4j();
        $result = $check->check();

        $this->assertInstanceOf('ZendDiagnostics\Result\Failure', $result);
    }
}
 