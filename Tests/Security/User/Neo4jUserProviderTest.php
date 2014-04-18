<?php

namespace Frne\Bundle\Neo4jUserBundle\Security\User;

use Frne\Bundle\Neo4jUserBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class Neo4jUserProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \HireVoice\Neo4j\Repository|PHPUnit_Framework_MockObject_MockObject
     */
    private $repo;

    /**
     * @var Neo4jUserProvider
     */
    private $userProvider;

    public function setUp()
    {
        $this->repo = $this->getMockBuilder('HireVoice\Neo4j\Repository')
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->getMock();

        $this->userProvider = new Neo4jUserProvider($this->repo, 'Frne\Bundle\Neo4jUserBundle\Entity\User');
    }

    public function testLoadUserByUsername()
    {
        $this->repo
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('username' => 'johndoe'))
            ->will($this->returnValue(new User('johndoe')));

        $loadedUser = $this->userProvider->loadUserByUsername('johndoe');

        $this->assertInstanceOf('Frne\Bundle\Neo4jUserBundle\Entity\User', $loadedUser);
    }

    public function testThrowsExceptionWhileTryingToLoadUnknownUser()
    {
        $this->setExpectedException('Symfony\Component\Security\Core\Exception\UsernameNotFoundException');
        $this->userProvider->loadUserByUsername('johndoe');
    }

    public function testSupportsClassUser()
    {
        $this->assertTrue($this->userProvider->supportsClass('Frne\Bundle\Neo4jUserBundle\Entity\User'));
        $this->assertFalse($this->userProvider->supportsClass('Symfony\Component\Security\Core\User\User'));
    }

    public function testRefreshUser()
    {
        $returnedUser = new User('johndoe', '1234', 'spdo8fgspd9f8gs', array('ROLE_USER', 'ROLE_ADMIN'));
        $this->repo
            ->expects($this->any())
            ->method('findOneBy')
            ->with(array('username' => 'johndoe'))
            ->will($this->returnValue($returnedUser));

        $loadedUser = $this->userProvider->loadUserByUsername('johndoe');
        $this->assertEquals(array('ROLE_USER', 'ROLE_ADMIN'), $loadedUser->getRoles());

        $returnedUser->setRoles(array('NO_ROLE'));

        $refreshedUser = $this->userProvider->refreshUser($loadedUser);
        $this->assertEquals(array('NO_ROLE'), $refreshedUser->getRoles());
    }

    public function testRefreshInvalidUserThrowsException()
    {
        $this->setExpectedException('Symfony\Component\Security\Core\Exception\UnsupportedUserException');

        $user = $this->getMockForAbstractClass('Symfony\Component\Security\Core\User\UserInterface');
        $this->userProvider->refreshUser($user);
    }
}
 