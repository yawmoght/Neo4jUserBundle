<?php

namespace Frne\Bundle\Neo4jUserBundle\Model;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class Neo4jUserManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Neo4jUserManager
     */
    private $userManager;

    /**
     * @var Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $encoderFactory;

    public function setUp()
    {
        $this->encoderFactory = $this
            ->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userManager = new Neo4jUserManager(
            $this->encoderFactory
        );
    }

    public function testCreateUser()
    {
        $this->assertInstanceOf('Symfony\Component\Security\Core\User\UserInterface', $this->userManager->createUser());
    }

    public function testUpdatePassword()
    {
        $user = $this->userManager->createUser();

        $user
            ->setPlainPassword('1234')
            ->setSalt('sodfgsodf78gsdo8f7gsdo');

        $this->encoderFactory
            ->expects($this->any())
            ->method('getEncoder')
            ->with($user)
            ->will($this->returnValue(new MessageDigestPasswordEncoder()));

        $this->assertEquals(null, $user->getPassword());

        $this->userManager->updatePassword($user);
        $this->assertEquals(
            'SFnq38rwcU+xSD8vIHHig7pfzOG6hI+zntV6Qe/ughA0OTlmo7GLoBUwMIWfVytfzAIPJVgBZXupEueKqDjOCg==',
            $user->getPassword()
        );
    }
}
 