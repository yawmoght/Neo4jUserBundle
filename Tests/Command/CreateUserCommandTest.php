<?php

namespace Frne\Bundle\Neo4jUserBundle\Command;

use Frne\Bundle\Neo4jUserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\app\AppKernel;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class CreateUserCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnMissingUsername()
    {
        $this->setExpectedException('InvalidArgumentException');
        $commandTester = $this->getCommandTester();
        $commandTester->execute(
            array(
                'command' => 'neo4j:user-bundle:create-user',
                '--password' => 'testpassword'
            )
        );
    }

    public function testExceptionOnMissingPassword()
    {
        $this->setExpectedException('InvalidArgumentException');
        $commandTester = $this->getCommandTester();
        $commandTester->execute(
            array(
                'command' => 'neo4j:user-bundle:create-user',
                '--username' => 'testuser',
            )
        );
    }

    public function testCreateUserWithDefaultRole()
    {
        $commandTester = $this->getCommandTester();
        $commandTester->execute(
            array(
                'command' => 'neo4j:user-bundle:create-user',
                '--username' => 'testuser',
                '--password' => 'testpassword'
            )
        );

        $this->assertRegExp('/testuser/', $commandTester->getDisplay());
        $this->assertRegExp('/testpassword/', $commandTester->getDisplay());
        $this->assertRegExp('/ROLE_USER/', $commandTester->getDisplay());
    }

    public function testCreateUserWithCustomtRoles()
    {
        $commandTester = $this->getCommandTester();
        $commandTester->execute(
            array(
                'command' => 'neo4j:user-bundle:create-user',
                '--username' => 'testuser',
                '--password' => 'testpassword',
                '--roles' => 'ROLE_FOO,ROLE_BAR'
            )
        );

        $this->assertRegExp('/testuser/', $commandTester->getDisplay());
        $this->assertRegExp('/testpassword/', $commandTester->getDisplay());
        $this->assertRegExp('/ROLE_FOO/', $commandTester->getDisplay());
        $this->assertRegExp('/ROLE_BAR/', $commandTester->getDisplay());
    }

    /**
     * @return CommandTester
     */
    private function getCommandTester()
    {
        $kernel = new \Frne\Bundle\Neo4jUserBundle\Tests\App\AppKernel('test', true);
        // mock the Kernel or create one depending on your needs
        $application = new Application($kernel);
        $commandObject = new CreateUserCommand();

        $usermanager = $this->getMockForAbstractClass('Frne\Bundle\Neo4jUserBundle\Model\UserManager');
        $usermanager->expects($this->any())
            ->method('createUser')
            ->will($this->returnValue(new User()));
        $em = $this->getMockBuilder('HireVoice\Neo4j\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $commandObject->setDeps($usermanager, $em);
        $application->add($commandObject);

        $command = $application->find('neo4j:user-bundle:create-user');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }
}